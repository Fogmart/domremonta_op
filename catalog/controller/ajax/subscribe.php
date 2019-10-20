<?php
class ControllerAjaxSubscribe extends Controller
{

    public function ajaxSend()
    {
        //проверяем была ли отправлена форма. Если нет, завершаем работу контроллера
        if ($this->request->server['REQUEST_METHOD'] !== 'POST') {
            return false;
        }

        //извлекаем имя домена из настроек CMS.
        $parse = parse_url($this->config->get('config_url'));
        $domain = $parse['host'];
        //поскольку ответ отправляем в формате JSON, необходимо установить заголовок
        $this->response->addHeader('Content-Type: application/json');
        //здесь валидация того, что форма отправлена именно через AJAX.
        //просто как дополнительная защита от спама
        if (!isset($this->request->server['HTTP_X_REQUESTED_WITH']) &&
            strtolower($this->request->server['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest'
        ) {
            $output = json_encode([
                'type' => 'error',
                'text' => 'Sorry Request must be Ajax POST',
            ]);

            $this->response->setOutput($output);
            return false;
        }


        $email = $this->request->post['email'];
        if (strlen($email) < 4) {
            $output = json_encode([
                'type' => 'error',
                'text' => 'Слишком короткий email',
            ]);
            $this->response->setOutput($output);
            return false;
        }
        $this->db->query("INSERT INTO news_subscribe (email) values ('".$email."')");

        $this->response->setOutput(json_encode([
            'type' => 'message',
            'text' => 'Спасибо за подписку!',
        ]));

    }
}