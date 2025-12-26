                <div class="card mb-3">
                    <img src="{{ asset('images/pobuna.jpeg') }}" class="w-2_12 mb-8 shadow-xl" alt="top_logo">
                    <div class="card-body">
                        <div class="list_container">
                            <div class="fixed">
                                <h5 class="card-title">List of Valetudinarians</h5>
                                <p class="card-text">You will find here all the info about subjects in the system</p>
                            </div>
                            <div class="flex-item">
                                <!--button id="search_btn" class="btn btn-info" type="button">Search</button-->
                                <input id="search" type="text" name="search"/>      <!--required/-->
                                <img id="search_img" src="{{ asset('images/search-icon-no-background-hd-260.png') }}" alt="Search" width=3% height=auto />
                            </div>    
                        </div>
                    <table id="thetable" class="table">
                        <thead>
                            <tr>
                                <th scope="col">Select</th>
                                <th scope="col">Name</th>
                                @if($layout == 'createfromvaleid')
                                <th scope="col">Organizacija</th>
                                <th scope="col">Mesto</th>
                                @else
                                <th scope="col">
                                    <select id="party_filter" name="party_filter" class="form-control">
                                        <option value="">Select Party</option>
                                        @foreach($parties as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </th>
                                <th scope="col">
                                    <select id="category_filter" name="category_filter" class="form-control">
                                        <option selected value="">Select Location</option>
                                        @foreach($locations as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </th>
                                @endif
                                <th scope="col">At Disposition</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($valetudinarians as $valetudinarian)
                            <tr>             <!-- $param == 1 is Vale check list (one raw of inputed Vale) with Events input -->
                                <!--td><input type="checkbox" @-if(($valetudinarian->used_by_other == 1) || ($layout == 'add')) disabled @-endif name="vale_selected[]" value="{--{ $valetudinarian->id }}"></td-->
                                <td><input type="checkbox" @if($valetudinarian->used_by_other == 1) disabled @endif @if($param == 1) disabled checked @endif name="vale_selected[]" value="{{ $valetudinarian->id }}"></td>
                                <td>
                                    <a href="{{ url('/show/'.$valetudinarian->id) }}">
                                    {{ $valetudinarian->first_name }}
                                    {{ $valetudinarian->last_name }}
                                    </a>
                                </td>
                                <td>{{ $valetudinarian->party_name }}</td>
                                <td>{{ $valetudinarian->location_name }}</td>
                                @if($valetudinarian->used_by_other == 0)
                                    <td><img src="{{ asset('images/gui_check_yes_icon_157194.png') }}" alt="True" width=12% height=auto /></td>                               
                                @else
                                    <td><img src="{{ asset('images/gui_check_no_icon_157196.png') }}" alt="False" width=12% height=auto /></td>
                                @endif
                                <td>
                                    @if (Auth::user())
                                    <!--a href="{ { url('/calendar-equ/'.$valetudinarian->id) }}" class="border-b-2 pb-2 border-dotted italic text-green-500">
                                        <img src="{ { asset('images/Calendar-icon.png') }}" alt="Calendar" width=5% height=auto />
                                    </a-->

                                    <!--&emsp; 
                                    <a href="{***{ url('/destroy-equevent/'.$equipment->equ_events_id) }}" class="btn btn-sm btn-danger">Delete</a-->
                                    @else
                                        <p>
                                            Please login, in order to show items.
                                        </p>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    </div>

                </div> 