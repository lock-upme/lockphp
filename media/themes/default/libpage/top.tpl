<section class="container-fluid navcontainer">
    <div class="container">
        <nav class="nav clearfix">
            <div class="nav-l fl">
                <a href="http://www.nahehuo.com" title="哪合伙-快速帮您找工作" class="logo"><img src="{{#site_template#}}/images/logoblue.png" alt="哪合伙-快速帮您找工作"></a>
            </div>
            <div class="nav-middle fl">
                <ul class="navlist fl">
                    <li class="active"><a href="/" title="哪合伙-首页">首页</a></li>
                    <li> <a href="/space/home" title="档案">档案 </a></li>
                </ul>
            </div>
            <div class="nav-r fr">
                <!--登录成功-->
                {{if $user}}
                <div class="head">
                    <a href="/space/info">
                        <img src="{{$user.avatorUrl}}" alt="" class="avatar mr10">
                        <span class="txt">{{$user.nickname}}</span>
                    </a>
                    <span class="ml10 mr10 ce5">|</span>
                    <a href="/user/logout" class="out">退出</a>
                </div>
                {{else}}
                <!--未登录-->
                <div class="register f16">
                    <a href="/user/register">注册</a>
                    <span class="ml10 mr10 ce5">|</span>
                    <a href="/user/login" class="out">登录</a>
                </div>
                {{/if}}
            </div>
        </nav>
    </div>
</section>