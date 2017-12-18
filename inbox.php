<?php
include ("proses_Login1.php");

if (!isset($_SESSION['access_token'])) {
    header("location: index.php");
}
?>

<!DOCTYPE html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>RTV-Mail</title>

    <link href="bootstrap/css/bootstrap-theme-united.min.css" rel="stylesheet">
    <link href="css/dashboard.css" rel="stylesheet">
    <link href="css/starter-template.css" rel="stylesheet">
    <link href="css/fileinput.css" media="all" rel="stylesheet" type="text/css" />
    <link href="css/style.css" rel="stylesheet">

    <!--    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
        <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css">-->
    <style>
        iframe {
            width: 100%;
            border: 0;
            min-height: 80%;
            height: 600px;
            display: flex;
        }
    </style>
</head>

<body>

    <nav class="navbar navbar-inverse navbar-fixed-top">
        <div class="container-fluid">
            <div class="navbar-header">
                <button class="navbar-toggle collapsed" data-toggle="collapse" aria-expanded="false" aria-controls="navbar">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="home.php"><h3>RTV-Mail</h3></a>
            </div>
            <div id="navbar" class="collapse navbar-collapse">
                <ul class="nav navbar-nav navbar-right">
                    <?php #if (isset($_SESSION['access_token'])) {?>
                    <li><a href="?logout" class="size"><img style="margin-right: 10px; margin-top: -2px;" src="img/logout.png" width="25" height="25">Logout</a></li>

                    <?php #} else { ?>

                    <?php #}?>
                </ul>

            </div><!--/.nav-collapse -->
        </div>
    </nav>


    <div class="container-fluid">
        <div class="navbar-default sidebar" role="navigation">
            <div class="col-sm-2 col-md-2 sidebar">
                <ul class="nav" id="side-menu">
                    <!--   <li>
                            <a href="#compose-modal" data-toggle="modal" id="compose-button" class="linkweb">
                                <img style="margin-right: 10%;" src="img/write.png" width="30" height="30">Compose 1</a>
                        </li> !-->
                    <li>
                        <a class="linkweb" href="compose.php"><img style="margin-right: 10%;" src="img/write.png" width="30" height="30">  Compose </a>
                    </li>
                    <li>
                        <a class="linkweb" href="inbox.php"><img style="margin-right: 10%;" src="img/inbox.png" width="30" height="30"></i>  Inbox</a>
                    </li>
                    <li>
                        <a class="linkweb" href="sent.php"><img style="margin-right: 10%;" src="img/send.png" width="30" height="30">  Sent Mail</a>
                    </li>
                    <li>
                        <a class="linkweb" href="dekrip.php"><img style="margin-right: 10%;" src="img/file-dec.png" width="30" height="30">  Dekripsi</a>
                    </li>
                    <li>
                        <a class="linkweb" href="help.php"><img style="margin-right: 10%;" src="img/help.png" width="30" height="30">  Help</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div id="page-wrapper">  
        <div class="col-lg-12">
            <h3 class="page-header">Inbox<img id="imagestyle" src="img/inbox.png"></h3>
        </div>
        <!-- /.col-lg-12 -->
        <div class="col-lg-12">
            <div class="panel-body">

                <button id="authorize-button" class="btn btn-info hidden">Authorize</button><p></p>

                <table class="table table-striped table-hover table-inbox well well-lg">
                    <thead>
                        <tr>
                            <th style="padding-right:195px;">From</th>
                            <th style="padding-right:170px;">Subject</th>
                            <th style="padding-right:225px;">Date/Time</th>
                    <!--    <th style="padding-right:175px;">Document</th> -->
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>                
    </div>

    <div class="modal fade" id="reply-modal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title">Reply</h4>
                </div>
                <form onsubmit="return sendReply();">
                    <input type="hidden" id="reply-message-id" />

                    <div class="modal-body">
                        <div class="form-group">
                            <input type="text" class="form-control" id="reply-to" disabled />
                        </div>

                        <div class="form-group">
                            <input type="text" class="form-control disabled" id="reply-subject" disabled />
                        </div>

                        <div class="form-group">
                            <textarea class="form-control" id="reply-message" placeholder="Message" rows="10" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for='uploaded_file'>Select A File To Upload:</label>
                            <input type="file" name="uploaded_file">
                        </div>
                        <div class="writekey">Password
                            <input type="password" class="form-input" name="txtpassword" placeholder="Minimal ... karakter">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" id="reply-button" class="btn btn-primary">Send</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="bower_components/jquery/dist/jquery.min.js"></script>

    <script src="//code.jquery.com/jquery-1.11.3.min.js"></script>

    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>

    <!-- =========================== INI ADALAH inbox.js ===========================  !-->
    <script type="text/javascript">
                    var clientId = '798244529357-t28af42bhqbth7pahil3ugc9ij3i8qpr.apps.googleusercontent.com';
                    var apiKey = 'AIzaSyCIvACRUEiB8mlhzjcb7w3u4a4lE4fSKj4';
                    var scopes =
                            'https://www.googleapis.com/auth/gmail.readonly ' +
                            'https://www.googleapis.com/auth/gmail.send';

                    function handleClientLoad() {
                        gapi.client.setApiKey(apiKey);
                        window.setTimeout(checkAuth, 1);
                    }

                    function checkAuth() {
                        gapi.auth.authorize({
                            client_id: clientId,
                            scope: scopes,
                            immediate: true
                        }, handleAuthResult);
                    }

                    function handleAuthClick() {
                        gapi.auth.authorize({
                            client_id: clientId,
                            scope: scopes,
                            immediate: false
                        }, handleAuthResult);
                        return false;
                    }

                    function handleAuthResult(authResult) {
                        if (authResult && !authResult.error) {
                            loadGmailApi();
                            $('#authorize-button').remove();
                            $('.table-inbox').removeClass("hidden");
                            $('#compose-button').removeClass("hidden");
                        } else {
                            $('#authorize-button').removeClass("hidden");
                            $('#authorize-button').on('click', function() {
                                handleAuthClick();
                            });
                        }
                    }

                    function loadGmailApi() {
                        gapi.client.load('gmail', 'v1', displayInbox);
                    }

                    function displayInbox() {
                        var request = gapi.client.gmail.users.messages.list({
                            'userId': 'me',
                            'labelIds': 'INBOX',
                            'maxResults': 10
                        });
                        request.execute(function(response) {
                            $.each(response.messages, function() {
                                var messageRequest = gapi.client.gmail.users.messages.get({
                                    'userId': 'me',
                                    'id': this.id
                                });
                                messageRequest.execute(appendMessageRow);
                            });
                        });
                    }

                    function getAttachments(userId, message, callback) {
                        var parts = message.payload.parts;
                        for (var i = 0; i < parts.length; i++) {
                            var part = parts[i];
                            if (part.filename && part.filename.length > 0) {
                                var attachId = part.body.attachmentId;
                                var request = gapi.client.gmail.users.messages.attachments.get({
                                    'id': attachId,
                                    'messageId': message.id,
                                    'userId': userId
                                });
                                request.execute(function(attachment) {
                                    callback(part.filename, part.mimeType, attachment);
                                });
                            }
                        }
                    }
                    function appendMessageRow(message) {

                        var attachment_id = message.payload.parts[1]['body']['attachmentId'];
                        var attachment_name = message.payload.parts[1]['filename'];

                        console.log(message.payload.parts);
                        $('.table-inbox tbody').append(
                                '<tr>\
            <td>' + getHeader(message.payload.headers, 'From') + '</td>\
            <td>\
              <a href="#message-modal-' + message.id +
                                '" data-toggle="modal" id="message-link-' + message.id + '">' +
                                getHeader(message.payload.headers, 'Subject') +
                                '</a>\
            </td>\
            <td>' + getHeader(message.payload.headers, 'Date') + '</td>\
          </tr>'
                                );
                        var reply_to = (getHeader(message.payload.headers, 'Reply-to') !== '' ?
                                getHeader(message.payload.headers, 'Reply-to') :
                                getHeader(message.payload.headers, 'From')).replace(/\"/g, '&quot;');

                        var reply_subject = 'Re: ' + getHeader(message.payload.headers, 'Subject').replace(/\"/g, '&quot;');
                        $('body').append(
                                '<div class="modal fade" id="message-modal-' + message.id +
                                '" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">\
            <div class="modal-dialog modal-lg">\
              <div class="modal-content">\
                <div class="modal-header">\
                  <button type="button"\
                          class="close"\
                          data-dismiss="modal"\
                          aria-label="Close">\
                    <span aria-hidden="true">&times;</span></button>\
                  <h4 class="modal-title" id="myModalLabel">' +
                                getHeader(message.payload.headers, 'Subject') +
                                '</h4>\
                </div>\
                <div class="modal-body">\
                  <iframe id="message-iframe-' + message.id + '" srcdoc="<p>Loading...</p>">\
                  </iframe>\
                </div>\
                <div class="modal-footer">\
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>\
                  <button type="button" class="btn btn-primary reply-button" data-dismiss="modal" data-toggle="modal" data-target="#reply-modal"\
                  onclick="fillInReply(\
                    \'' + reply_to + '\', \
                    \'' + reply_subject + '\', \
                    \'' + getHeader(message.payload.headers, 'Message-ID') + '\'\
                    );"\
                  >Reply</button>\
                </div>\
              </div>\
            </div>\
          </div>'
                                );
                        $('#message-link-' + message.id).on('click', function() {
//        var ifrm = $('#message-iframe-' + message.id)[0].contentWindow.document;
//        $('body', ifrm).html(getBody(message.payload));
                            //alert(isinya)
                            //panjang = isinya.length;
                            //alert(panjang);
                            var ifrm = $('#message-iframe-' + message.id)[0].contentWindow.document;
                            var subjeknya = getHeader(message.payload.headers, 'Subject');
                            var isinya = getBody(message.payload);
                            var attachmentnya = attachment_id;
                            var message_id = message.id;
                            isi = subjeknya.length;
                            var isinyaterakhirdah;
                            //alert(isi);
                            if (isi == 32) {
                                /*dekript*/
                                $.ajax({
                                    url: 'ajax.php',
                                    //data: 'action=dekrip&key=' + subjeknya + '&cipher=' + isinya,
                                    data : {
                                        action : 'dekrip',
                                        key : subjeknya,
                                        cipher : isinya,
                                        attachmentnya : attachmentnya,
                                        message_id : message_id,
                                        attachment_name : attachment_name
                                    },
                                    
                                    type: 'POST',
                                    success: function(data) {

                                        //alert(outoupt);
                                        isinyaterakhirdah = data;
                                        $('body', ifrm).html(data);

                                    },
                                });
                            } else {
                                $('body', ifrm).html(isinya);
                            }



                            /*dekript
                             $.ajax({
                             url:'ajax.php',
                             data:'action=dekrip&key='+subjeknya+'&cipher='+isinya,
                             type:'post',
                             success:function(output){
                             //alert(outoupt);
                             $('body', ifrm).html(output);
                             
                             },
                             
                             });
                             */
                            
                            $('body', ifrm).html(isinyaterakhirdah);

                        });
                    }

                    function sendEmail()
                    {
                        $('#send-button').addClass('disabled');

                        sendMessage(
                                {
                                    'To': $('#compose-to').val(),
                                    'Subject': $('#compose-subject').val()
                                },
                        $('#compose-message').val(),
                                composeTidy
                                );

                        return false;
                    }

                    function composeTidy()
                    {
                        $('#compose-modal').modal('hide');

                        $('#compose-to').val('');
                        $('#compose-subject').val('');
                        $('#compose-message').val('');

                        $('#send-button').removeClass('disabled');
                    }

                    function sendReply()
                    {
                        $('#reply-button').addClass('disabled');

                        sendMessage(
                                {
                                    'To': $('#reply-to').val(),
                                    'Subject': $('#reply-subject').val(),
                                    'In-Reply-To': $('#reply-message-id').val()
                                },
                        $('#reply-message').val(),
                                replyTidy
                                );

                        return false;
                    }

                    function replyTidy()
                    {
                        $('#reply-modal').modal('hide');

                        $('#reply-message').val('');

                        $('#reply-button').removeClass('disabled');
                    }

                    function fillInReply(to, subject, message_id)
                    {
                        $('#reply-to').val(to);
                        $('#reply-subject').val(subject);
                        $('#reply-message-id').val(message_id);
                    }

                    function sendMessage(headers_obj, message, callback)
                    {
                        var email = '';

                        for (var header in headers_obj)
                            email += header += ": " + headers_obj[header] + "\r\n";

                        email += "\r\n" + message;

                        var sendRequest = gapi.client.gmail.users.messages.send({
                            'userId': 'me',
                            'resource': {
                                'raw': window.btoa(email).replace(/\+/g, '-').replace(/\//g, '_')
                            }
                        });

                        return sendRequest.execute(callback);
                    }

                    function getHeader(headers, index) {
                        var header = '';
                        $.each(headers, function() {
                            if (this.name.toLowerCase() === index.toLowerCase()) {
                                header = this.value;
                            }
                        });
                        return header;
                    }

                    function getBody(message) {
                        var encodedBody = '';
                        if (typeof message.parts === 'undefined')
                        {
                            encodedBody = message.body.data;
                        }
                        else
                        {
                            encodedBody = getHTMLPart(message.parts);
                        }
                        encodedBody = encodedBody.replace(/-/g, '+').replace(/_/g, '/').replace(/\s/g, '');
                        return decodeURIComponent(escape(window.atob(encodedBody)));
                    }

                    function getHTMLPart(arr) {
                        for (var x = 0; x <= arr.length; x++)
                        {
                            if (typeof arr[x].parts === 'undefined')
                            {
                                if (arr[x].mimeType === 'text/html')
                                {
                                    return arr[x].body.data;
                                }
                            }
                            else
                            {
                                return getHTMLPart(arr[x].parts);
                            }
                        }
                        return '';
                    }
    </script>

    <script src="https://apis.google.com/js/client.js?onload=handleClientLoad"></script>
</body>
</html>