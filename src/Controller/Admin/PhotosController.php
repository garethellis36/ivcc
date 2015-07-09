<?php
namespace App\Controller\Admin;

use App\Controller\AppController;
use Cake\Error\Debugger;
use App\Lib\PhotoUtility;
use Cake\Log\Log;
use Cake\Validation\Validator;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 */
class PhotosController extends AppController
{

    public function add()
    {

    }

    public function upload()
    {
        $this->autoRender = false;

        $this->request->allowMethod("ajax");

        if (empty($this->request->data["file"]["name"])) {
            echo json_encode([
                "success" => false,
                "errors" => "No file provided"
            ]);
            return;
        }

        //validate photo type
        $validator = new Validator();
        $validator->add("file", "validImage", [
            "rule" => ["uploadedFile", [
                "types" => ["image/jpeg", "image/png"],
                "maxSize" => 5000000]
            ],
            "message" => "You may only upload JPG and PNG files no larger than 5MB"
        ]);
        $errors = $validator->errors($this->request->data);

        if (!empty($errors)) {
            echo json_encode([
               "success" => false,
                "errors" => $errors["file"]
            ]);
            return;
        }

        try {
            //resize and create thumbnail
            $parts = explode(".", $this->request->data["file"]["name"]);
            $ext = array_pop($parts);

            $photo = new PhotoUtility($this->request->data["file"]["tmp_name"], $ext);
            $photo->createThumbnail();
            $photo->resize();

            //save to database
            $data = [
                "Photos" => [
                    "name" => $photo->getName(),
                    "title" => ""
                ]
            ];

            $photoEntity = $this->Photos->newEntity();
            $photoEntity = $this->Photos->patchEntity($photoEntity, $data);

            if (empty($photoEntity->errors()['date'])) {
                $photoEntity->date = $photo->getDate();
            }

            if ($this->Photos->save($photoEntity)) {
                echo json_encode([
                    "success" => true,
                    "errors" => null
                ]);
                return;
            }

        } catch (\Exception $e) {
            Log::write(LOG_ERR, $e->getMessage());
            echo json_encode([
                "success" => false,
                "errors" => [
                    "exception" => $e->getMessage()
                ]
            ]);
            return;
        }

    }


    /**
     * Delete method
     *
     * @param string|null $id News id.
     * @return void Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $photo = $this->Photos->get($id);
        if ($this->Photos->delete($photo)) {
            $this->Flash->success('Photo deleted.');

            $path = WWW_ROOT . "img" . DS . "photos" . DS;
            $this->deleteFile($path . $photo->name);

            $path .= "thumbs" . DS;
            $this->deleteFile($path . $photo->name);

        } else {
            $this->Flash->error('The photo could not be deleted. Please, try again.');
        }
        return $this->redirect("/photos");
    }

    private function deleteFile($path)
    {
        if (file_exists($path)) {
            return unlink($path);
        }
        return false;
    }

    public function edit($id = null)
    {
        $photo = $this->Photos->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {

            $photo = $this->Photos->patchEntity($photo, $this->request->data);
            if ($this->Photos->save($photo)) {
                $this->Flash->success('Photo saved.');
                return $this->redirect("/photos/view/" . $id);
            } else {
                $this->Flash->error('The photo could not be saved. Please, try again.');
            }
        }
        $this->set(compact('photo'));
        $this->set('_serialize', ['photo']);

    }

}
