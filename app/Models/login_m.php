<?php

namespace App\Models;



class login_m extends core_m
{
    public function index()
    {
        //require_once("meta_m.php");
        $data = array();
        $data["message"] = "";
        $data["hasil"] = "";
        $data['masuk'] = 0;


        $identity = $this->db->table("identity")->get()->getRow();
        // dd($identity->identity_twitter);

        if (isset($_POST["user_email"]) && isset($_POST["password"])) {
            $builder = $this->db->table("user")
                ->where("user_email", $this->request->getPost("user_email"));
            $user1 = $builder
                ->get();

            // echo $this->db->getLastQuery();die;

            // define('production',$this->db->database);
            // echo production;
            // $lastquery = $this->db->getLastQuery();
            // echo $lastquery;
            // die;
            //    $query = $this->db->query("SELECT * FROM `user`  WHERE `user_nik` = 'ihsan.dulu@gmail.com'");
            //     echo $query->getFieldCount();
            // die;

            $halaman = array();
            if ($user1->getNumRows() > 0) {
                foreach ($user1->getResult() as $user) {
                    $password = $user->user_password;
                    // Dekripsi
                    // Kunci dan metode enkripsi
                    $key = "ihsandulu123456"; // Kunci rahasia (jangan hardcode di produksi)
                    $method = "AES-256-CBC";
                    $datak = base64_decode($password);
                    $iv_dec = substr($datak, 0, openssl_cipher_iv_length($method));
                    $encrypted_data = substr($datak, openssl_cipher_iv_length($method));
                    $decrypted = openssl_decrypt($encrypted_data, $method, $key, 0, $iv_dec);
                    // if (password_verify($this->request->getVar("password"), $password)) {
                    // echo $this->request->getVar("password") . " ==> " . $decrypted;die;
                    if ($this->request->getVar("password") == $decrypted) {

                        // echo $user->user_name;die;
                        $this->session->set("id", $user->id);
                        $this->session->set("user_name", $user->user_name);
                        $this->session->set("position_id", $user->position_id);
                        $this->session->set("position_administrator", $user->position_id);
                        $this->session->set("user_picture", $user->user_picture);
                        $this->session->set("identity_id", $identity->identity_id);
                        $this->session->set("identity_name", $identity->identity_name);
                        $this->session->set("identity_logo", $identity->identity_logo);
                        $this->session->set("identity_phone", $identity->identity_phone);
                        $this->session->set("identity_address", $identity->identity_address);
                        $this->session->set("identity_about", $identity->identity_about);
                        $this->session->set("identity_email", $identity->identity_email);

                         //tambahkan modul di sini                         
                        $pages = $this->db->table("positionpages")
                            ->join("pages", "pages.pages_id=positionpages.pages_id", "left")
                            ->where("position_id", $user->position_id)
                            ->get();
                        foreach ($pages->getResult() as $pages) {
                            // $halaman = array(109, 110, 111, 112, 116, 117, 118, 119, 120, 121, 122, 123, 159, 173,187,188,189,190,192,196);
                            $halaman[$pages->pages_id]['act_read'] = $pages->positionpages_read;
                            $halaman[$pages->pages_id]['act_create'] = $pages->positionpages_create;
                            $halaman[$pages->pages_id]['act_update'] = $pages->positionpages_update;
                            $halaman[$pages->pages_id]['act_delete'] = $pages->positionpages_delete;
                            $halaman[$pages->pages_id]['act_approve'] = $pages->positionpages_approve;
                        }


                        //tambahkan modul di sini                         
                        $pages = $this->db->table("tranprod")
                            ->join("product", "product.product_id=tranprod.product_id", "left")
                            ->where("tranprod.user_id", $user->id)
                            ->get();
                        foreach ($pages->getResult() as $pages) {
                            $halaman[$user->id][] = $pages->product_id;
                        }
                        $this->session->set("halaman", $halaman);
                        $data["hasil"] = " Selamat Datang  " . $user->user_name;
                        $this->session->setFlashdata('hasil', $data["hasil"]);
                        $data['masuk'] = 1;
                        // echo "";die;
                    } else {
                        $data["hasil"] = " Password Salah !";
                        // $data["hasil"]=password_verify('123456', '123456').">>>".$this->request->getVar("password").">>>".$password;
                    }
                }
            } else {
                $data["hasil"] = " Email tidak ditemukan !";
            }
        }

        $this->session->setFlashdata('message', $data["hasil"]);
        return $data;
    }
}
