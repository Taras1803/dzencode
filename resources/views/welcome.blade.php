<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laravel</title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet" type="text/css">
    <!-- Styles -->
    <link rel="stylesheet" href="{{asset('css/style.css')}}" type="text/css">
    <link rel="stylesheet" href="{{asset('css/main.css')}}" type="text/css">
    <!--jQuery-->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
</head>
<body>

<svg xmlns="http://www.w3.org/2000/svg" style="border: 0 !important; clip: rect(0 0 0 0) !important;
 height: 1px !important; margin: -1px !important; overflow: hidden !important; padding: 0 !important;
  position: absolute !important; width: 1px !important;" class="root-svg-symbols-element">
    <symbol id="reply" viewBox="0 0 51 32">
        <path d="M0 16l19.2-16v9.6c14.4 0 25.6 3.2 32 19.2-9.6-8-19.2-9.6-32-6.4v9.6l-19.2-16z"></path>
    </symbol>
</svg>

<div class="flex-center position-ref full-height">
    @if (Route::has('login'))
        <div class="top-right links">
            @auth
                <a href="{{ url('/home') }}">Home</a>
                @else
                    <a href="{{ route('login') }}">Login</a>

                    @if (Route::has('register'))
                        <a href="{{ route('register') }}">Register</a>
                    @endif
                    @endauth
        </div>
    @endif

    <div class="content" style="height: 90%">
        <div class="comments js-comments" data-block-id="video_comments_video_comments">
            <div class="heading">
                <strong class="heading__title">
                    Comments <span class="heading__mark">{{$count}}</span>
                </strong>
            </div>
            <div id="video_comments_video_comments">
                <ul class="comments__list js-list-comments">
                    @foreach($comments as $comment)
                        <li class="comments__item js-item" data-comment-id="{{ $comment->id }}">
                            <div class="comment">
                                <div class="comment__avatar">
                                    <div class="avatar avatar--user">
                                        <img src="/images/default.jpg">
                                    </div>
                                </div>
                                <div class="comment__content">
                                    <div class="comment__header">
                                        <span class="comment__user">{{$comment->user_name}}</span>
                                        <span class="comment__time">{{$comment::get_time_passed($comment->created_at)}} ago</span>
                                        <span class="comment__time comment__time_reply scrollable" style="cursor: pointer">
                                            <svg class="icon icon--reply">
                                                <use xlink:href="#reply"></use>
                                            </svg>
                                        </span>
                                    </div>
                                    <div class="comment__body">
                                        <p>&laquo;{{$comment->comment}}&raquo;</p>
                                    </div>
                                </div>
                            </div>
                            @if ($comment->children)
                                <div class="children_comments">
                                    <ul class="comments__list js-list-comments"
                                        @foreach($comment->children as $comment_child)
                                            id="video_comments_video_comments_items">
                                            <li class="comments__item js-item" data-comment-id="{{$comment_child->id}}">
                                                <div class="comment">
                                                    <div class="comment__avatar">
                                                        <div class="avatar avatar--user">
                                                            <img src="/images/default_child.jpg">
                                                        </div>
                                                    </div>
                                                    <div class="comment__content">
                                                        <div class="comment__header">
                                                            <span class="comment__user_child">{{$comment_child->user_name}}</span>
                                                            <span class="comment__time">{{$comment_child::get_time_passed($comment_child->created_at)}} ago</span>
                                                        </div>
                                                        <div class="comment__body">
                                                            <p>&laquo;{{$comment_child['comment']}}&raquo;</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
        <script>
            $('.icon--reply').click(function () {
                var commentId = $(this).parents('.js-item').attr('data-comment-id');
                $("input[name='comment_parent_id']").val(commentId);
                $('html, body').animate({
                    scrollTop: $("#comment_message").offset().top
                }, 1000);
            })
        </script>
        <form class="comments__form form" method="post" style="margin-top: 20px;">
            <input type="hidden" name="action" value="add_comment"/>
            <input type="hidden" name="comment_parent_id" value="0"/>
            <div class="message-success is-hidden">
                Thank you! Your comment has been submitted for review.
            </div>
            <div class="message-error is-hidden"></div>
            <div class="form__group">
                <label for="comment_username" class="label">Your name *</label>
                <div class="form__hold">
                    <input type="text" id="name" required name="name" maxlength="30" class="field" placeholder="please enter name to make your comment personalized"/>
                </div>
            </div>
            <div class="form__group">
                <label for="email" class="label">Your email *</label>
                <div class="form__hold">
                    <input type="email" id="email" required name="email" maxlength="30" class="field" placeholder="please enter email"/>
                </div>
            </div>
            <div class="form__group">
                <label for="home_url" class="label">Your home page</label>
                <div class="form__hold">
                    <input type="url" id="home_url" name="home_url" maxlength="30" class="field" placeholder="please enter your home page"/>
                </div>
            </div>
            <div class="form__group">
                <label for="comment_message" class="label">Comment message:</label>
                <div class="form__hold">
                    <textarea id="comment_message" required name="comment" class="field field--area" placeholder="Add your comment"></textarea>
                    <div class="validate validate--error"></div>
                </div>
            </div>
            <div class="form__group">
                <div class="captcha">
                    <span class="captcha__hint">Please confirm that you are a Human by entering security code from the image below.</span>
                    <img src="https://en.cnnamador.com/captcha/logon/?rand=1545756709" alt="Captcha image" class="captcha__img">
                    <div class="captcha__action">
                        <label for="comments_code" class="label is-required">Security code</label>
                        <div class="form__hold">
                            <input type="text" id="comments_code" class="field" name="code" placeholder="Security code" autocomplete="off">
                            <div class="validate validate--error"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form__group">
                <button class="btn btn--primary">
                    <span class="btn__text">Post Comment</span>
                </button>
            </div>
        </form>

    </div>
</div>
</body>
</html>
