<?php
namespace App\Controller\Admin;

use App\Controller\AppController;
use Cake\Error\Debugger;
use App\Lib\PhotoUtility;
use Cake\Log\Log;

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
            echo "fail";
            return;
        }

        //validate photo type

        //resize photo and copy to web dir
        try {

            $parts = explode(".", $this->request->data["file"]["name"]);
            $ext = array_pop($parts);

            $photo = new PhotoUtility($this->request->data["file"]["tmp_name"], $ext);
            $photo->createThumbnail();
            $photo->resize();

            //save to database
            $data = [
                "Photos" => [
                    "name" => $photo->getName(),
                    "type" => $photo->getType(),
                    "title" => "",
                    "user_id" => $this->Auth->user("id")
                ]
            ];

            $photoEntity = $this->Photos->newEntity();
            $photoEntity = $this->Photos->patchEntity($photoEntity, $data);

            if (empty($photoEntity->errors()['date'])) {
                $photoEntity->date = $photo->getDate();
            }

            if ($this->Photos->save($photoEntity)) {
                echo "success";
                return;
            }

        } catch (\Exception $e) {
            Log::write(LOG_ERR, $e->getMessage());
            echo "fail";
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

            $path = WWW_ROOT . "photos" . DS;
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


}
