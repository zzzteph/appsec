<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<script href="{{asset('js/jquery.js')}}" type="text/javascript"></script>
    <link rel="stylesheet" href="{{asset('css/bulma.css')}}">
	<link rel="stylesheet" href="{{asset('css/style.css')}}">

	 <script defer src="{{asset('fontawesome/js/all.js')}}" type="text/javascript"></script>
	 <script src="{{asset('js/vue.js')}}" type="text/javascript"></script>
	 <script src="{{asset('js/axios.min.js')}}" type="text/javascript"></script>
	 <script src="{{asset('js/tinymce.min.js')}}" type="text/javascript"></script>
	 	    <style type="text/css" media="screen">
      body {
        display: flex;
        min-height: 100vh;
        flex-direction: column;
      }
      #wrapper {
        flex: 1;
      }
    </style>

  </head>
<body>
	<section class="section pt-3 pb-3	">
 <div class="container is-size-5">

<nav class="navbar" role="navigation" aria-label="main navigation">
  <div class="navbar-brand">
    <a class="navbar-item" href="{{route('main')}}">
  <img src="/logo.png" class="image">
    </a>

    <a role="button" class="navbar-burger" aria-label="menu" aria-expanded="false" data-target="navbarBasicExample">
      <span aria-hidden="true"></span>
      <span aria-hidden="true"></span>
      <span aria-hidden="true"></span>
    </a>
  </div>

  <div id="navbarBasicExample" class="navbar-menu is-size-5">
    <div class="navbar-start">

      <a class="navbar-item" href="{{route('users')}}">
        Scoreboard
      </a>

@auth


	@if(Auth::user()->has_tool)

		@if(is_null(Auth::user()->tool_vm()))
        <a class="navbar-item">
			<form method="POST"  action="{{route('user-tool-start')}}">
				@csrf
				<button class="button is-primary">
				<span class="icon-text">
					<span>Remote Desktop</span>
					  <span class="icon">
						<i class="fas fa-play"></i>
					  </span>
					</span>
				</button>
			</form>
		</a>
		@else

			@if(Auth::user()->tool_vm()->status=="running")
			<div id="uservm" class="navbar-item">
				<div class="field is-grouped">
					<p class="control">
			<a
v-bind:class = "(uservm.timeout > 45)?'button is-success':(uservm.timeout<=45 && uservm.timeout>30)?'button is-info':(uservm.timeout<=30 && uservm.timeout>15)?'button is-warning':'button is-danger'"

target="_blank" href="http://{{Auth::user()->tool_vm()->ip}}:6080/?autoconnect=true&password=123456">
				<span class="icon-text">
				<span>Remote Desktop</span>
				  <span class="icon">
					<i class="fas fa-sign-in-alt"></i>
				  </span>
	         <span>@{{ uservm.timeout }}</span>
          <span class="icon">
					<i v-bind:class = "(uservm.timeout > 45)?'fas fa-hourglass-start':(uservm.timeout<=45 && uservm.timeout>30)?'fas fa-hourglass-half':(uservm.timeout<=30 && uservm.timeout>15)?'fas fa-hourglass-end':'fas fa-hourglass'"></i>
				 </span>



				</span>

			</a>
			</p>


			<p class="control">
			<form method="POST"  action="{{route('user-tool-stop')}}">
			@method('DELETE')
			@csrf
            <button class="button is-danger">

			<span class="icon-text">
				  <span class="icon">
					<i class="fas fa-stop"></i>
				  </span>
				</span>
			</button>

			</form>
			</p>

				</div>
			</div>
			@else
			<a id="uservm" class="navbar-item">

			<button v-if="uservm.status =='todo' || uservm.status =='starting'" class="button is-primary">
			<span class="icon-text">
				  <span class="icon" >
					<i class="fas fa-hourglass-start fa-spin" v-if="100-uservm.progress> 70"></i>
					<i class="fas fa-hourglass-half fa-spin" v-else-if="100-uservm.progress<=70 && 100-uservm.progress>30"></i>
					<i class="fas fa-solid fa-hourglass-empty fa-spin" v-else></i>
				  </span>
				  <span>@{{ 100-uservm.progress }} left</span>
			</span>

			</button>
			<button v-if="uservm.status =='tostop' || uservm.status =='stopping'" class="button is-danger">
			<span class="icon-text">
				  <span class="icon">
					<i class="fas fa-spinner fa-spin"></i>
				  </span>
				</span>
			</button>
			</a>
			@endif
		@endif
	@endif


@endauth



@auth
	@if(Auth::user()->role==='admin')
      <div class="navbar-item has-dropdown is-hoverable">
        <a class="navbar-link">
          Cloud
        </a>




        <div class="navbar-dropdown">
          <a class="navbar-item" href="{{route('cloud')}}">
            Cloud
          </a>
          <a class="navbar-item" href="{{route('vms')}}">
            VMS
          </a>
          <a class="navbar-item" href="{{route('admin-users')}}">
            Users
          </a>
		  <a class="navbar-item" href="{{route('admin-tools')}}">
            Tools
          </a>


          <hr class="navbar-divider">
          <a class="navbar-item" href="{{route('monitor-task')}}">
            Jobs monitoring
          </a>
		            <a class="navbar-item" href="{{route('monitor-tool')}}">
            Tools monitoring
          </a>
        </div>
      </div>




	      <div class="navbar-item has-dropdown is-hoverable">
        <a class="navbar-link">
          CMS
        </a>




        <div class="navbar-dropdown">


	       <a class="navbar-item" href="{{route('admin-view-lessons')}}">
            Lessons
          </a>



		 <a class="navbar-item" href="{{route('admin-list-topics')}}">
           Topics
          </a>






        </div>
      </div>





	@endif
@endauth


    </div>

       <div class="navbar-end">
      <div class="navbar-item">





	  @auth








	  	          <div class="buttons">


	@if(!is_null(Auth::user()->current_user_lab_vm()) && Auth::user()->current_user_lab_vm()->status!="terminated")

	<div id="timer">
		  <a class="button is-primary is-size-5 mr-3" href="{{route('view-lesson',[
		  'topic_id'=>Auth::user()->current_user_lab_vm()->node()->topic->id,
		  'node_id'=>Auth::user()->current_user_lab_vm()->node()->node_id
		  ])}}"  v-if="vm.status == 'running'">

<span class="icon-text">

<span>Current task</span>
  <span class="icon">
    <i v-if="vm.timeout > 45" class="fas fa-hourglass-start"></i>
	<i v-else-if="vm.timeout<=45 && vm.timeout>15" class="fas fa-hourglass-half"></i>
	<i v-else-if="vm.timeout<=15 && vm.timeout>5" class="fas fa-hourglass-end"></i>
	<i v-else class="fas fa-hourglass"></i>
  </span>
<span>@{{ vm.timeout }}</span>



</span>
</a>


<a class="navbar-item mr-3 " v-if="vm.status != 'running' && vm.status != 'terminated'" href="{{route('view-lesson',[
		  'topic_id'=>Auth::user()->current_user_lab_vm()->node()->topic->id,
		  'node_id'=>Auth::user()->current_user_lab_vm()->node()->node_id
		  ])}}">
<span class="icon-text" >

<span v-if="vm.status == 'todo' || vm.status == 'starting'">Starting task</span>
<span v-if="vm.status == 'tostop' || vm.status == 'stopping'">Stopping task</span>

   <progress class="progress is-small is-warning mt-1	" v-if="vm.status=='todo'"  :value="vm.progress" max="100">@{{vm.progress}}%</progress>
            <progress class="progress is-small is-success mt-1	" v-if="vm.status=='starting'" :value="vm.progress" max="100">@{{vm.progress}}%</progress>
            <progress class="progress is-small is-warning mt-1	" v-if="vm.status=='tostop'" :value="vm.progress" max="100">@{{vm.progress}}%</progress>
            <progress class="progress is-small is-danger mt-1	" v-if="vm.status=='stopping'" :value="vm.progress" max="100">@{{vm.progress}}%</progress>
</span>
</a>

			</div>




	@endif

	       <a class="navbar-item" href="#">
		   
		   
		   
		   		          <span class="icon-text is-align-items-center">
 
                  <span> {{Auth::user()->user_statistic->score/100}}</span>
				  
				   <span class="icon">
					<i class="fas fa-coins"></i>
                  </span>
				  
                  </span>
          </a>

          <a class="button is-primary-light is-size-5" href="{{ route('user-page',['id' => Auth::id()]) }}">
            My page
          </a>
			<form method="POST"  action="{{route('logout')}}">
		@method('DELETE')
			@csrf
            <button class="button is-danger is-size-5">Exit</button>
			</form>
        </div>


	   @else
	          <div class="buttons">
          <a class="button is-light is-size-5" href="/login">
            Log in
          </a>
        </div>

	   @endauth

      </div>





    </div>
  </div>
</nav>



	@if ($errors->any() || Session::has('success'))

	 <div class="container is-size-5">
@if ($errors->any())
							<article class="message is-danger">
  <div class="message-header">
    <p>Error</p>
  </div>
  <div class="message-body has-text-left">
   <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
  </div>
</article>
@endif
@if (Session::has('success'))

							<article class="message is-success">
  <div class="message-header">

  </div>
  <div class="message-body has-text-left">
   <ul>
<li>{{ Session::get('success') }}</li>
        </ul>
  </div>
</article>



@endif


 </div>

@endif

</div>
</section>
<div id="wrapper">
