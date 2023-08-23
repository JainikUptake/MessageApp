<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Laravel</title>

    
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
  

        <style>
            .chat-row {
                margin: 50px;
            }


             ul {
                 margin: 0;
                 padding: 0;
                 list-style: none;
             }


             ul li {
                 padding:8px;
                 background: #928787;
                 margin-bottom:20px;
             }


             /* ul li:nth-child(2n-2) {
                background: #c3c5c5;
             } */


             .chat-input {
                 border: 1px soild lightgray;
                 border-top-right-radius: 10px;
                 border-top-left-radius: 10px;
                 padding: 8px 10px;
                 color:#000000;
                 background-color: rgb(184, 236, 246);
             }
        </style>
    </head>
    <body>
        {{-- <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}"> --}}
        <div class=" alert alert-success alert-dismissible fade show row" role="alert">
            <div class="col-3">
                    <img src={{ asset('image/'.auth()->user()->images) }} style="width: 70px;
                    height: 70px;
                    border-radius: 100%;" alt="bg" />
            </div>
            <div class="col-3">
                {{ auth()->user()->name }}
            </div>
            <div class="col-3">
                <select class="form-select border-0" name="receiver_id" id="receiver_id" >
                    <option value=""disabled selected>Choose...</option>
                    @foreach ($getUser as $User)
                        <option value="{{ $User['id'] }}">{{ $User['name'] }}</option>
                    @endforeach

                </select>
            </div>
            <div class="col-3">
                <a href="{{ route('logout') }}" class="btn btn-secondary">Logout</a>
            </div>
        </div>
        <div class="container">
            <div class="row chat-row">
                <div class="chat-content">
                    <ul id="chatMessages">
                        <!-- Chat messages will be appended here -->
                    </ul>
                </div>
                <div class="chat-box">
                    <div class="chat-input" id="chatInput" contenteditable></div>
                </div>
            </div>
        <script src="https://code.jquery.com/jquery-3.7.0.js" integrity="sha256-JlqSTELeR4TLqP0OG9dxM7yDPqX1ox/HfgiSLBj8+kM=" crossorigin="anonymous"></script>
        <script src="https://cdn.socket.io/4.6.0/socket.io.min.js" integrity="sha384-c79GN5VsunZvi+Q/WObgk2in0CbZsHnjEqvFxC5DxHn9lTfNce2WW6h2pH6u/kF+" crossorigin="anonymous"></script>
<!-- Include necessary scripts -->

    {{-- <script>
       

        $(function() {
            const userId = {{ auth()->user()->id }};
            // var receiverId = $('#receiver_id').val();
            let receiverId;
            $('#receiver_id').on('change', function () {
                var receiverIds = this.value;
                receiverId = receiverIds
                console.log(receiverId);
            });
            const socket = io('http://127.0.0.1:3000'); // Replace with your server address
        
            // Join user's room on connection
            socket.emit('joinChat', userId);
        
            const chatInput = $('#chatInput');
            const chatMessages = $('#chatMessages');



            // Send message to server when Enter key is pressed
            chatInput.keypress(function(e) {
                let message = $(this).text();
                if (e.which === 13 && !e.shiftKey) {
                    let csrfToken = $('meta[name="csrf-token"]').attr('content');
                    // Send the message to the server using POST with the CSRF token
                    $.ajax({
                        url: '/save-chat-message',
                        method: 'POST',
                        data: {
                            _token: csrfToken,
                            user_id: userId,
                            message: message,
                            receiver_id:receiverId,
                        },
                        success: function(response) {
                            // After saving, clear the input field
                            chatInput.text('');
                        }
                    });
                    return false;
                }
            });

            $.get(`/get-chat-history`, function (messages) {
            messages.forEach(function (message) {
                chatMessages.append(`<li>${message.message}</li>`);
            });
        });

        // Receive and display message from server
        socket.on('sendChatToClient', (message) => {
            console.log(message);
            chatMessages.append(`<li>${message.message}</li>`);
        });


        });
    </script> --}}
    <script>
        $(function() {
            const userId = {{ auth()->user()->id }};
            let receiverId;
    
            $('#receiver_id').on('change', function () {
                receiverId = this.value;
            });
    
            const socket = io('http://127.0.0.1:3000'); // Replace with your server address
            socket.emit('joinChat', userId);
    
            const chatInput = $('#chatInput');
            const chatMessages = $('#chatMessages');
    
            chatInput.keypress(function(e) {
                let message = $(this).text();
                if (e.which === 13 && !e.shiftKey) {
                    socket.emit('sendChatToServer', message);
                    let csrfToken = $('meta[name="csrf-token"]').attr('content');
                    $.ajax({
                        url: '/save-chat-message',
                        method: 'POST',
                        data: {
                            _token: csrfToken,
                            user_id: userId,
                            message: message,
                            receiver_id: receiverId,
                        },
                        success: function(response) {
                            chatInput.text('');
                        }
                    });
                    return false;
                }
            });
            
            // Listen for incoming messages from the socket
            socket.on('sendChatToClient', (message) => {
                console.log(message);
                chatMessages.append(`<li>${message}</li>`);
            });
            // $.get(`/get-chat-history`, function (messages) {
            //     messages.forEach(function (message) {
            //         chatMessages.append(`<li>${message.message}</li>`);
            //     });
            // });
    
        });
    </script>
    
    </body>
</html>
