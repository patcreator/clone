<?php 
    include_once 'app/auth/isAuth.php';
    include_once 'app/system/cogs/db.php';
    include_once 'app/system/cogs/functions.php';
    include_once 'app/api/db_helper.php';
    $path ='/clone/whatsapp/';
 ?>
<?php 
    $id = $_SESSION['user_id'];
    $user = executeQuery("SELECT * FROM users WHERE id = $id")['data'];
    if (!$user) {
        session_destroy();
        header("location:login");
    }
    $profile = executeQuery("SELECT * FROM user_profile WHERE user_id = '$id'")['data'];
    $message = executeQuery("SELECT * FROM message where sender = $id or receiver = $id")['data'];
    // debug($message);
    // exit;
 ?>

 <!DOCTYPE html>
 <html data-bs-theme="dark">
 <head>
     <meta charset="utf-8">
     <meta name="viewport" content="width=device-width, initial-scale=1">
     <title>Whatapp clone</title>
     <link rel="stylesheet" type="text/css" href="/clone/whatsapp/app/vendors/fontawesome/css/font-awesome.min.css">
      <link rel="stylesheet" type="text/css" href="/clone/whatsapp/app/vendors/bootstrap/css/bootstrap.min.css">
      <script src="/clone/whatsapp/app/vendors/js/jquery.min.js"></script>
      <style>
            *{
                padding: 0;
                margin: 0;
                box-sizing: border-box;
            }
              .bg-purple{
                background: var(--bs-purple) !important;
              }
              .text-purple{
                color: var(--bs-purple) !important;
              }
              .link-purple{
                color: var(--bs-purple) !important;
              }
            input{
                border-width: 2px !important;
            }
            input:focus{
                box-shadow: none !important;
                outline: none !important;
                border-color: white !important;
            }
            body{
                background-color: black;
                color: whitesmoke;
            }
          .item{
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
            border-radius: 50%;
            padding: 1.2rem;
            margin: 0.5rem;
            height: 2rem;
            width: 2rem;
            cursor: pointer;
            background-color: #222;
          }
          .item a{
            color: whitesmoke;
          }
          .item .tooltip{
            position: absolute;
          }
          .items-container{
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
            flex-direction: column;
            height:calc(100vh - 2.5rem);width: 5vw;
            background-color: #000;

          }
          .badge{
            position: absolute;
            top: -0.5rem;
            right: -0.2rem;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            border: 2px solid #000;
            height: 1rem;
            width: 1rem;
            padding: 0.6rem;
          }
          /*.square{
            height: 100px;
            width: 100px;
          }*/
          /*.square.sm{
            height: 1rem;
            width: 1rem;
          }*/
          .red{
            background-color: red;
          }
          .blue{
            background-color: var(--bs-purple);
          }
          .bg-000{
            background-color: #000;
          }
          .hover-red:hover{
            background: #533 !important;
          }
          .chat:hover{
            background: #222 !important;
          }
          .chats:hover{
            overflow-y: auto !important;
          }
          .chats{
            overflow-y: hidden !important;
            height:68vh !important;overflow-x: hidden !important;
          }
          aside{
            /*border: 1px solid #333;*/
          }
          .content{
            border: 1px solid #333;
            flex-grow: 1;
          }
          .bubble-l,.bubble-r{
            position: relative;
            max-width: 45%;
            display: flex;
            align-self: end;
          }
          .bubble-r{
            align-self: start;
          }
          .bubble-l:after,.bubble-r:after{
            content:'';
            position: absolute;
            top: 0rem;
            border-right: 1rem solid transparent;
            border-left: 1rem solid transparent;
            border-bottom: 1rem solid transparent;
            border-top: 1rem solid var(--bs-purple);
            border-radius: 0.2rem;
            right: -0.5rem;
            height: 2rem;
            width: 2rem;
          }
          .bubble-r:after{
            border-top: 1rem solid var(--bs-dark);
            border-radius: 0.2rem;
            left: -0.5rem;
          }
          .box:focus{
            box-shadow: none;
            outline: none;
            border: none;
          }
          .hover-show{
            z-index: 11111;
            background-color: var(--bs-light);
            min-width: 1rem;
            border-radius: 1rem;
            margin-left: 1rem;
            position: absolute;
            left: 1.7rem;
            top: 50%;
            font-size: small;
            white-space: nowrap;
            color: var(--bs-dark) !important;
            padding-left:1rem;
            padding-right: 1rem;
            transform: translateY(-50%);
            padding-top: 0.3rem;
            padding-bottom: 0.3rem;
            box-shadow: 0rem 0rem 1rem var(--bs-dark);
            transition: 0.5s;
            opacity: 0;
            visibility: hidden;
          }
          .item:hover .hover-show{
            visibility: visible;
            opacity: 1;
          }
          .bg-active{
            background-color: #333 !important;
          }
          a{
            text-decoration: none;
          }
      </style>
 </head>
 <body style="height:100vh; overflow: hidden;">

    <header class="p-1 bg-000 border-bottom">
        <div>
            <!-- <img src="app/system/filemanager/logos/logo-color.svg" style="filter: saturate(0) brightness(3);"> -->
            <i class="fa fa-comments h4"></i>
            <strong>URUGANIRIRO</strong>
        </div>
    </header>

    <div style="display:flex;">
        <div class="items-container border-end ">
            <div>
                <div class="item">
                    <span class="circle blue square sm badge">7</span>
                    <a href=""><i class="fa fa-comments"></i></a>
                    <span class="hover-show">Chats</span>
                </div>
                <div class="item">
                    <span class="circle red square sm badge">1</span>
                    <a href=""><i class="fa fa-phone"></i></a>
                    <span class="hover-show">Calls</span>
                </div>
                <div class="item">
                    <a href=""><i class="fa fa-photo"></i></a>
                    <span class="hover-show">Status</span>
                </div>
                <div class="item">
                    <a href=""><i class="fa fa-comment"></i></a>
                    <span class="hover-show">Channels</span>
                </div>
                <div class="item">
                    <a href=""><i class="fa fa-users"></i></a>
                    <span class="hover-show">Communities</span>
                </div>
                <div class="h-divider"></div>
                <div class="item">
                    <a href=""><i class="fa fa-briefcase"></i></a>
                    <span class="hover-show">Tools</span>
                </div>
                <div class="item">
                    <a href=""><i class="fa fa-money"></i></a>
                    <span class="hover-show">Advertise on facebook</span>
                </div>
            </div>
            <div class="float-bottom">
               <div class="item">
                    <a href=""><i class="fa fa-photo"></i></a>
                    <span class="hover-show">Media</span>
                </div>
                <div class="h-divider"></div>
               <div class="item">
                    <a href=""><i class="fa fa-cog"></i></a>
                    <span class="hover-show">Settings</span>
                </div>
               <div class="item">
                    <a href=""><i class="fa fa-user"></i></a>
                    <span class="hover-show">Profile</span>
                </div>
            </div>
        </div>
        <main class="w-100 d-flex">
            <aside style="height: 100%;width: 30%;" class="p-1 ">
                <div class="side-header p-2 d-flex justify-content-between">
                    <h5>Chats</h5>
                    <div class="d-flex">
                        <button class="btn btn-dark rounded-pill"><i class="fa fa-plus"></i></button>
                        <div data-bs-theme="dark" class="dropdown">
                            <button data-bs-toggle="dropdown"  class="btn btn-dark ms-2 px-3 rounded-pill"><i class="fa fa-ellipsis-v"></i></button>
                            <div class="dropdown-menu p-2 shadow rounded-4 py-3">
                                <div class="dropdown-item rounded-3 py-2 px-3" role="button">
                                    <i class="me-3 fa fa-briefcase"></i>
                                    <span>Business tools</span>
                                </div>
                                <div class="dropdown-item rounded-3 py-2 px-3" role="button">
                                    <i class="me-3 fa fa-users"></i>
                                    <span>New group</span>
                                </div>
                                <div class="dropdown-item rounded-3 py-2 px-3" role="button">
                                    <i class="me-3 fa fa-facebook"></i>
                                    <span>Advertise with facebook</span>
                                </div>
                                <div class="dropdown-item rounded-3 py-2 px-3" role="button">
                                    <i class="me-3 fa fa-th"></i>
                                    <span>Catalog</span>
                                </div>
                                <div class="dropdown-item rounded-3 py-2 px-3" role="button">
                                    <i class="me-3 fa fa-archive"></i>
                                    <span>Archived</span>
                                </div>
                                <div class="dropdown-item rounded-3 py-2 px-3" role="button">
                                    <i class="me-3 fa fa-bolt"></i>
                                    <span>Quicky replies</span>
                                </div>
                                <div class="dropdown-item rounded-3 py-2 px-3" role="button">
                                    <i class="me-3 fa fa-star"></i>
                                    <span>Started messages</span>
                                </div>
                                <div class="dropdown-item rounded-3 py-2 px-3" role="button">
                                    <i class="me-3 fa fa-tag"></i>
                                    <span>Labels</span>
                                </div>
                                <div class="dropdown-divider"></div>
                                <div class="dropdown-item rounded-3 py-2 px-3" role="button">
                                    <i class="me-3 fa fa-lock"></i>
                                    <span>App lock</span>
                                </div>
                                <a href="<?= $path ?>logout" class="dropdown-item rounded-3 py-2 px-3 bg-transparent hover-red" role="button">
                                    <i class="me-3 fa fa-sign-out"></i>
                                    <span>Logout</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- seachbox -->
                <div class="d-flex px-2 py-2 position-relative">
                    <button class="btn position-absolute disabled text-muted border-0" disabled><i class="fa fa-search"></i></button>
                    <input type="text" name="search" class="form-control rounded-pill" style="padding-left: 2rem;" placeholder="Seach or start a new chat">
                </div>
                <div class="btns oveflow-auto width-100 d-flex px-2 py-2">
                    <a href="<?= $path?>" class="btn <?= (!isset($_GET['favorite']) and !isset($_GET['group']) and !isset($_GET['unread']))?'btn-dark':'btn-outline-dark'  ?> text-muted btn-sm me-2 rounded-pill ">All</a>
                    <a href="<?= $path?>?unread" class="btn <?= isset($_GET['unread'])?'btn-dark':'btn-outline-dark'  ?> text-muted btn-sm me-2 rounded-pill ">Unread</a>
                    <a href="<?= $path?>?favorite" class="btn <?= isset($_GET['favorite'])?'btn-dark':'btn-outline-dark'  ?> text-muted btn-sm me-2 rounded-pill ">Favorites</a>
                    <a href="<?= $path?>?group" class="btn <?= isset($_GET['group'])?'btn-dark':'btn-outline-dark'  ?> text-muted btn-sm me-2 rounded-pill ">Groups</a>
                </div>
                <!-- warning -->
                <div class="alert alert-warning rounded-4 d-flex p-0 px-3 mx-2 d-none">
                        <i class="h1 fa fa-warning me-3 mt-2"></i>
                        <div style="line-height: 1.2rem;" class="py-2">
                            <strong>Computer not connected</strong>
                            <div>Make sure your computer has an active internet connection. <a href="#" class="link-purple fw-bold">Reconnect</a></div>
                        </div>
                </div>

                <!-- chats -->
                <div class="chats list-group">
                    <?php 
                        if (isset($_GET['unread'])) {
                            $sql = "SELECT *, m.id as mid, count(*) as nbsent FROM message m inner join users u on u.id=m.sender where m.receiver = $id and m.status in('pending','sent') group by m.sender";
                            $msg_txt = "Messages";
                            $sent_by_me = '';
                        }elseif (isset($_GET['favorite'])) {
                            $sql = "SELECT *,m.id as mid FROM favorites f inner join message m on f.item_id=m.id inner join users u on m.receiver = u.id where m.sender = $id or m.receiver = $id";
                            $msg_txt = "Favorites";
                            $sent_by_me = '';
                        }elseif (isset($_GET['group'])) {
                            $sql = "SELECT *,m.id as mid FROM message m inner join users u on u.id=m.sender where m.sender = $id and m.is_group = 1";
                            $msg_txt = "Groups";
                            $sent_by_me = '';
                        }else{
                            $sql = "SELECT *, m.id as mid, count(*) as nbsent FROM message m inner join users u on u.id=m.sender where m.receiver = $id and m.status = 'pending' group by m.sender";
                            $msg_txt = "Mesages";
                            $sent_by_me = executeQuery("SELECT *, m.id as mid FROM message m inner join users u on u.id=m.receiver where m.sender = $id group by m.receiver ")['data'];
                        }
                        $exclude = [];
                        $data = executeQuery($sql)['data'];
                        if (empty($data) && (isset($_GET['favorite']) or isset($_GET['group']) or isset($_GET['unread']))) {
                            echo "<div class='alert alert-danger fade show mx-2 p-2 rounded-3 alert-dismissable bg-dark border-dark'>".$msg_txt." not found <button class=' btn-close float-end' data-bs-dismiss='alert'></button></div>";
                        }?>
                    <?php foreach ($data as $item) { ?>
                     <a href="<?= $path ?>?r=<?= $item['receiver'] ?>&s=<?= $item['sender'] ?>" class="list-group-item mb-2 bg-transparent hover d-flex border-0 rounded-4 mx-2 chat <?= ([$item['receiver'],$item['sender']] == [$_GET['r']??0,$_GET['s']??0])?'bg-active':'' ?>" role="button">
                        <img src="/clone/whatsapp/app/system/filemanager/images/avatars/<?= empty($item['image'])?'default.png':$item['image'] ?>" style="height:3rem;width: 3rem;border-radius: 50%;object-fit: cover;" class="img-fluid me-3"> 
                        <div class="position-relative w-100">
                            <div class="position-absolute end-0 top-0">
                            <strong class="text-purple"><?= date("h:i", strtotime($item['created_at'])) ?></strong>
                                <span class="bg-purple d-inline-block px-2 fs-6 p-0 rounded-pill"><?= $item['nbsent']??1 ?></span>
                            </div>
                            <strong title="<?= $item['username'] ?>"><?= $item['username'] ?></strong>
                            <div>
                                <?= strlen($item['content'])>20?substr($item['content'],0,20).'...':$item['content']; ?>
                            </div>
                        </div>                     
                    </a>
                     <?php $exclude[] =[$item['receiver'],$item['sender']];  } ?>
                     <?php 
                     if (!isset($_GET['favorite']) and !isset($_GET['group']) and !isset($_GET['unread'])) {
                     
                     foreach ($sent_by_me as $item) { 
                        if (in_array([$item['sender'],$item['receiver']], $exclude)) continue;
                        ?>
                     <a href="<?= $path ?>?r=<?= $item['receiver'] ?>&s=<?= $item['sender'] ?>" class="list-group-item mb-2 bg-transparent hover d-flex border-0 rounded-4 mx-2 chat <?= ([$item['receiver'],$item['sender']] == [$_GET['r']??0,$_GET['s']??0])?'bg-active':'' ?>" role="button">
                        <img src="/clone/whatsapp/app/system/filemanager/images/avatars/<?= empty($item['image'])?'default.png':$item['image'] ?>" style="height:3rem;width: 3rem;border-radius: 50%;object-fit: cover;" class="img-fluid me-3"> 
                        <div class="position-relative w-100">
                            <div class="position-absolute end-0 top-0">
                            <strong class="text-purple"><?= date("h:i", strtotime($item['created_at'])) ?></strong>
                            </div>
                            <strong title="<?= $item['username'] ?>"><?= $item['username'] ?></strong>
                            <div>
                                <?= strlen($item['content'])>20?substr($item['content'],0,20).'...':$item['content']; ?>
                            </div>
                        </div>                     
                    </a>
                     <?php }} ?>
                </div>
            </aside>
            <div class="content p-2" style="width: 70%;">
                <?php 
                    if (isset($_GET['r']) and !empty($_GET['r']) and is_numeric($_GET['r']) and isset($_GET['s']) and !empty($_GET['s']) and is_numeric($_GET['s'])) {
                        $not_me = $_GET['r'] == $id?$_GET['s']:$_GET['r'];
                        $sender = $_GET['s'];
                        $receiver = $_GET['r'];
                        $messages = executeQuery("SELECT *,m.id as mid FROM message m inner join users u on u.id=m.sender where m.sender = $sender and m.receiver = $receiver or m.sender = $receiver and m.receiver = $sender ORDER by m.created_at asc")['data'];
                        $not_me_data = executeQuery("SELECT * FROM users where id = '$not_me' limit 1")['data'][0];
                        ?>
                    <header class="d-flex justify-content-between px-2">
                    <a href="#" class="d-flex text-white text-decoration-none">
                        <img src="/clone/whatsapp/app/system/filemanager/images/avatars/<?= empty($not_me_data['image'])?'default.png':$not_me_data['image']; ?>" style="height:2rem;" class="rounded-pill me-2">
                        <div style="line-height:0.1rem;">
                            <h6><?= $not_me_data['username'] ?></h6>
                            <small class="text-muted">Click to open user information</small> <!-- last seen today at 10:50 -->
                        </div>
                    </a>
                    <div class="d-flex">
                        <a href="#" class="p-2 px-3 rounded-pill btn btn-outline-dark border-0 text-muted m-1">
                            <i class="fa fa-video-camera"></i>
                        </a>
                        <a href="#" class="p-2 px-3 rounded-pill btn btn-outline-dark border-0 text-muted m-1">
                            <i class="fa fa-phone"></i>
                        </a>
                        <a href="#" class="p-2 px-3 rounded-pill btn btn-outline-dark border-0 text-muted m-1">
                            <i class="fa fa-search"></i>
                        </a>
                        <div class="dropdown">
                            <button class="p-2 px-3 rounded-pill btn btn-outline-dark border-0 text-muted m-1" data-bs-toggle="dropdown"><i class="fa fa-ellipsis-v"></i></button>
                            <ul class="dropdown-menu dropdown-menu-end p-2 shadow rounded-4 py-3">
                              <li><a class="dropdown-item rounded-3 py-2 px-3" href="#"><i class="fa fa-info-circle me-2"></i> Contact Info</a></li>
                              <li><a class="dropdown-item rounded-3 py-2 px-3" href="#"><i class="fa fa-check-square me-2"></i> Select Messages</a></li>
                              <li><a class="dropdown-item rounded-3 py-2 px-3" href="#"><i class="fa fa-bell-slash me-2"></i> Mute Notifications</a></li>
                              <li><a class="dropdown-item rounded-3 py-2 px-3" href="#"><i class="fa fa-eye-slash me-2"></i> Disappearing Messages</a></li>
                              <li><a class="dropdown-item rounded-3 py-2 px-3" href="#"><i class="fa fa-heart me-2"></i> Add to Favorites</a></li>
                              <li><a class="dropdown-item rounded-3 py-2 px-3" href="#"><i class="fa fa-times-circle me-2"></i> Close Chat</a></li>
                              <li><a class="dropdown-item rounded-3 py-2 px-3" href="#"><i class="fa fa-thumbs-down me-2"></i> Report</a></li>
                              <li><a class="dropdown-item rounded-3 py-2 px-3" href="#"><i class="fa fa-minus-circle me-2"></i> Block</a></li>
                              <li><a class="dropdown-item rounded-3 py-2 px-3" href="#"><i class="fa fa-times me-2"></i> Clear Chat</a></li>
                              <li><a class="dropdown-item rounded-3 py-2 px-3" href="#"><i class="fa fa-trash me-2"></i> Delete Chat</a></li>
                            </ul>
                          </div>
                    </div>
                    
                </header>
                <!-- MESSAGES AREA -->
                <div class="messages p-3 d-flex flex-column" style="height: 70vh;overflow-x: hidden;overflow-y: auto;">
                    <?php 
                        foreach ($messages as $msg) {
                            if ($msg['sender'] == $id) {
                                ?>
                                  <div class="bubble-l p-2 bg-purple  float-end rounded-3 mt-2 mb-2">
                                  <?= $msg['content'] ?>
                                </div>
                                <?php
                            }else{
                                ?>
                                <div class="bubble-r p-2 bg-dark  rounded-3 mt-2 mb-5">
                                      <?= $msg['content'] ?>
                                  </div>
                            <?php 
                            }
                            
                        }
                     ?>
                </div>
                <form method="POST" action="@api/api?action=send">
                <div class="bg-dark mx-3 p-2 px-4 rounded-pill">
                    <div class="d-flex align-item-start">
                        <div class="me-1">
                            
                            <div class="dropdown">
                            <button class="btn btn-dark rounded-pill" data-bs-toggle="dropdown">
                                <i class="fa fa-plus"></i>
                            </button>
                                <ul class="dropdown-menu  p-2 shadow rounded-4 py-3">
                                  <li><a class="dropdown-item rounded-3 py-2 px-3" href="#"><i class="fa fa-file me-2"></i>Document</a></li>
                                  <li><a class="dropdown-item rounded-3 py-2 px-3" href="#"><i class="fa fa-image me-2"></i>Photo & Video</a></li>
                                  <li><a class="dropdown-item rounded-3 py-2 px-3" href="#"><i class="fa fa-video-camera me-2"></i>Camera</a></li>
                                  <li><a class="dropdown-item rounded-3 py-2 px-3" href="#"><i class="fa fa-microphone me-2"></i>Audio</a></li>
                                  <li><a class="dropdown-item rounded-3 py-2 px-3" href="#"><i class="fa fa-phone me-2"></i>Contact</a></li>
                                  <li><a class="dropdown-item rounded-3 py-2 px-3" href="#"><i class="fa fa-list me-2"></i>Poll</a></li>
                                  <li><a class="dropdown-item rounded-3 py-2 px-3" href="#"><i class="fa fa-calendar me-2"></i>Event</a></li>
                                  <li><a class="dropdown-item rounded-3 py-2 px-3" href="#"><i class="fa me-2">😎</i>New Sticker</a></li>
                                  <li><a class="dropdown-item rounded-3 py-2 px-3" href="#"><i class="fa fa-th me-2"></i>Catalog</a></li>
                                  <li><a class="dropdown-item rounded-3 py-2 px-3" href="#"><i class="fa fa-bolt me-2"></i>Quick Reply</a></li>
                                  <li><a class="dropdown-item rounded-3 py-2 px-3" href="#"><i class="fa fa-map me-2"></i>Location</a></li>
                                </ul>
                          </div>
                        </div>
                        <div class="me-1"> 
                             <div id="Emojis" class="d-none">
                                <?php include_once 'app/pages/sections/emojis.php'; ?>
                              </div>
                            <!-- Emoji/GIF/Stickers Dropdown -->
                              <div class="dropdown">
                                <button type="button" data-bs-toggle="dropdown" class="btn btn-dark rounded-pill" >
                                    <strong style="filter: saturate(0) brightness(1.9) invert(1);">😀</strong>
                                </button>
                                <ul class="dropdown-menu  p-2 shadow rounded-4 py-3">
                                  <li role="button" onclick="document.querySelector('#Emojis').classList.remove('d-none')" class="dropdown-item rounded-3 py-2 px-3">
                                      <i class="fa">😀</i>Emojis
                                  </li>
                                  <li role="button" class="dropdown-item rounded-3 py-2 px-3">
                                    <i class="fa">😎</i>
                                  GIFs</li>
                                  <li role="button" class="dropdown-item rounded-3 py-2 px-3">
                                    <i class="fa">💥</i>
                                  Stickers</li>
                                </ul>
                              </div>
                        </div>
                        
                            <input type="hidden" name="receiver" value="<?= $not_me ?>">
                            <input type="hidden" name="sender" value="<?= $id ?>">
                            <input type="hidden" name="is_group" value="0">
                            <div class="w-100">
                                <textarea class="form-control bg-transparent border-0 box" autofocus name="content" oninput="!this.value ? document.querySelector('.record').classList.remove('d-none'):document.querySelector('.record').classList.add('d-none');this.value ? document.querySelector('.plane').classList.remove('d-none'):document.querySelector('.plane').classList.add('d-none');" placeholder="Type a message" style="height: 2.3rem !important;min-height: 0.1rem !important;resize: none;overflow: auto;"></textarea>
                            </div>
                            <div class="send-btns d-flex">
                                <div class="record">
                                    <button type="button" class="btn"><i class="fa fa-microphone"></i></button>
                                </div>
                                <div class="plane d-none">
                                    <button class="btn"><i class="fa fa-paper-plane"></i></button>
                                </div>
                            </div>
                        
                    </div>
                </div>
                </form>
                        <?php
                    }else{
                        ?>
                       <div class="d-flex flex-column justify-content-center align-item-center text-center h-100 position-relative text-muted">
                            <div>
                                <i class="fa fa-comments display-1"></i>
                            </div>
                            <div class="h1">
                                Uruganiriro for windows
                            </div>
                            <div>
                                Grow, organise and manage your business account.
                            </div>
                            <div class="position-absolute bottom-0 mb-3 w-100">
                                <i class="fa fa-lock"></i>Your personal messsage are end-to-end encrypted.
                            </div>
                       </div>
                        <?php
                    }
                 ?>
            </div>
        </main>
    </div>
 </body>
  <script src="/clone/whatsapp/app/vendors/bootstrap/js/bootstrap.bundle.min.js"></script>
 </html>