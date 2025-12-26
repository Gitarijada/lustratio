<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>{{ config('app.name', 'Laravel') }}</title>

  <!-- Styles -->
  <link href="{{ asset('css/app.css') }}" rel="stylesheet">
  <!--link href="{-{ asset('tailwind.min.css') }}" rel="stylesheet"-->
  <link href="{{ asset('css/mine_mall.css') }}" rel="stylesheet">
  <link href="{{ asset('css/mine_tmp.css') }}" rel="stylesheet">  <!-- made for *list.blade -->

    <!-- JQuery-ajax search category store & other drop box & toastr for calendar as well -->
    <link href="{{ asset('css/toastr.min.css') }}" rel="stylesheet">
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/toastr.min.js') }}"></script>

<!-- Bootstrap JS (required for modal) Info modal -->
  <!--script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script  moved to asset('js/info-modal.js')-->
  <script src="{{ asset('js/info-modal.js') }}"></script>
  <link href="{{ asset('css/help-info-modal.css') }}" rel="stylesheet">   <!-- (required for modal) Help modal & Info modal -->

<style>
.table {
    table-layout: auto;
}

/*.navbar {
  position: fixed;
  /*top: 0; /* Adjust as needed for desired vertical position */
  /*left: 0; /* Adjust as needed for desired horizontal position 
  width: 100%; /* Ensure it spans the full width if desired 
  z-index: 1000; /* Ensure it stays above other content 
}*/
.navbar-nav > a:hover { /* Added LU */
  text-decoration: underline;
}

.col-my-auto {    /* use for description field...*/
  /*border: 1px solid blue;*/
  
  overflow: hidden;
  white-space: nowrap;
  text-overflow: ellipsis;
}

/*.table {
  width: 100%;
  margin-bottom: 1rem;
  color: #212529;
}
.table th,
.table td {
  padding: 0.75rem;
  vertical-align: top;
  border-top: 1px solid #dee2e6;
}*/

.col-occ-auto-td {    /* use for occupation field...*/
  /*border: 1px solid blue;*/
  max-width: 200px;
  overflow: hidden;
  white-space: nowrap;
  text-overflow: ellipsis;
}

.col-desc-auto-td {    /* use for description field...*/
  /*border: 1px solid blue;*/
  max-width: 300px;
  overflow: hidden;
  white-space: nowrap;
  text-overflow: ellipsis;
}

.dropbtn {

}

.col-my-cat {
  /*border: 1px solid rgb(255, 17, 0);*/
  overflow: hidden;
  white-space: nowrap;
  text-overflow: ellipsis;
}

.mw-logout {
  /*min-width: 150%;*/
  min-width: 70px;
}

.mb-3-date {
  max-width: 40%;
  margin-bottom: 1rem !important;
}

.container-show {
  display: flex;
  /* Optional: Add spacing or alignment */
  justify-content: space-around; /* Distributes space evenly around items */
  align-items: stretch; /* Vertically aligns items in the center */
  display: flex;
  justify-content: flex-start; /* Aligns items to the end of the main axis (right in a left-to-right context) */
  gap: 40px; /* Adds 20 pixels of space between all direct children */
}

.item-container {
  /* Add styling for individual div elements. Not in use at the moment */
  padding: 20px;
  border: 1px solid black;
  margin: 5px;
}

/*tables zebra striping*/
tr:nth-child(odd) {
  background-color: #f2f2f2; /* Light grey for odd rows */
}

tr:nth-child(even) {
  background-color: #ffffff; /* White for even rows */
}

.subject-line_A {
  color: #8B0000; /* dark red */ /* #007bff; A shade of blue */
  background-color: #ffcccc; /* Light red */
}
.subject-line_B {
  color: #FF8C00; /* dark orange */ 
  background-color: #FFD580; /* Light Orange */
}
.subject-line_C {
  color: #007bff; /* A shade of blue */
  background-color: #FFFFE0; /* Light yellow */
}
tr:nth-child(3) { 
  background-color: #ffcccc; /* Light red */
}
tr:nth-child(7) {
  background-color: #ccffcc; /* Light green */
}
tr:nth-child(21) {
  background-color: #FFFFE0; /* Light yellow */
}
tr:nth-child(25) {
  background-color: #FFD580; /* Light Orange */
}

.paginate_div
{
  text-align: center;
}
</style>
   
  <!--link rel="stylesheet" href="//code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css"-->    <!--date time picker-->
  <!-- Scripts -Calendar doesn't work with it-- SERG - fix to be done-->
  <!--script src="{***{ asset('js/app.js') }}" defer></script-->

  <!-- different style -->
  <!-- Bootstrap CSS Another CSS TRY-->
  <!--link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
  <link rel="stylesheet" href="{***{asset('../resources/css/style.css')}}"-->

  <!-- Add later looks good -- SERG - fix to be done ---- ADITIONAL CSS-->
  <!--link href="//netdna.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet"-->
  
  <!-- for Calendar / moved directly to blade file {{-- Scripts --}}
  <link href="{-{ asset('css/bootstrap.min.css') }}" rel="stylesheet" />
  <link href="{-{ asset('css/fullcalendar.min.css') }}" rel="stylesheet" />
  <link href="{-{ asset('css/toastr.min.css') }}" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" /-->
  
</head>