# Notifications
## _Biblioteca simples de notificações em Jquery_

Uma biblioteca simples para a exibição de notificações em seu projeto:

- 4 modos de exibição
- 3 modos de cores e status(success,warning,error)

## Installation

É necessario ter pré instado o jquery [Jquery](https://jquery.com/) 3.0+.

Install the dependencies and devDependencies and start the server.

```sh
<script src=""></script>
```

#### Iniciando

Script básico

```sh
$.notification(
    ["Send menssage one","Send message two"],
    {
        position: ['top', 'right'], //Array of message
        messageType: 'success', //message type
        timeView: 6000,//in milliseconds
        redirectAction: 'https://google.com',//or null
    }
)
```