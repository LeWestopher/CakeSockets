# CakeSockets
<h3>Elegant real-time communication for CakePHP</h3>

CakeSockets is a abstracted service-level integration between CakePHP and Socket.io, allowing inclusion of socket architecture into your CakePHP applications.

<h2>Requirements</h2>
* PHP 5.4+
* CakePHP 3.0
* Node.js + Socket.io

<h2>Usage</h2>
Currently, CakeSockets is available as a View class, with plans to create a Pushable behavior for event level access within models.  The SocketView is a simple extension of the JsonView class with its internal _serialize command.  Usage is like so:
```php
class YourController extends AppController
{
  public function yourAction()
  {
    $this->viewClass = 'Socket';
    $response = [
      'sockets' => 'are',
      'pretty' => 'freaking',
      'cool' => 'huh'
    ];
    $this->set([
      'response' => $response,
      '_socket' => 'response',
      '_serialize' => 'response'
    ]);
  }
}
```
The viewVar keyed to '_socket' within the ->set will be piped over to the Socket.io instance and socketed down to the appropriate namespaces and rooms (TODO: add namespace and room scoping).  The viewVar keyed to '_serialize' will be serialized and returned as a typical JSON responding endpoint would.  This gives us the ability to determine different behaviors for what data is delegated to where.
