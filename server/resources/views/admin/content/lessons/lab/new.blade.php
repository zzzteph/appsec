@include('include.header')

	 <section class="section">
		 <div class="container">

			<form method="POST" action="{{route('admin-add-new-lab-lesson')}}">
			@csrf

	
	<div class="field">
  <label class="label">Lesson name</label>
  <div class="control">
		<input class="input" type="text" placeholder="Lesson name" name="name" value="">
  </div>
</div>


<div class="field">
  <label class="label">Content</label>
  <div class="control">
 
<textarea class="textarea" placeholder="Textarea" name="description"></textarea>
	
  </div>
</div> 

<hr/>
	


<div class="field">
  <label class="label">Content</label>
  <div class="control">
 
<textarea class="textarea" placeholder="Textarea" id="content" name="content"></textarea>
	
  </div>
</div>



<div class="select">
  <select name="vm">
  @foreach ($vms as $vm)
    <option value="{{$vm->id}}">{{$vm->name}}</option>
 
	@endforeach
  </select>
</div>




<div class="field">
  <div class="control">
    <label class="checkbox">
		<input type="checkbox" name="published">
     Published
    </label>
  </div>
</div>



<div class="field">
  <div class="control">
    <button class="button is-success is-large">Save</button>
  </div>
</div>
</form>


    </div>

    </section>



@include('include.footer')