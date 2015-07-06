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
            Debugger::log($photo->getName());
        } catch (\Exception $e) {
            Log::write(LOG_ERR, $e->getMessage());
            echo "fail";
            return;
        }
        die();

        //save to database
        $data = [
          "Photos" => [
              "name" => $photo->getName(),
              "type" => $photo->getType()
          ]
        ];

        $photo = $this->Photos->newEntity();
        $photo = $this->Photos->patchEntity($photo, $data);
        $photo->user_id = $this->Auth->user("id");

        if ($this->Photos->save($photo)) {
            echo "success";
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
        } else {
            $this->Flash->error('The photo could not be deleted. Please, try again.');
        }
        return $this->redirect("/photos");
    }

}
