                <div class="card mb-3">
                    <img src="{{ asset('images/pobuna.jpeg') }}" class="w-2_12 mb-8 shadow-xl" alt="top_logo">
                    <div class="card-body">
                        <div class="list_container">
                            <div class="fixed"> {{-- VALELIST_CHECK.BLADE, ONLY HERE IS USED SO FARE, event.blade(parent) (in project) --}}
                                <h5 class="card-title">Adding Events for Valetudinarian</h5>
                                <!--p class="card-text">You will find here all the info about subjects in the system</p-->
                            </div>
                            <div class="flex-item">
                                <!--button id="search_btn" class="btn btn-info" type="button">Search</button-->
                                <input id="search" type="text" name="search">      <!--required/-->
                                <img id="search_img" src="{{ asset('images/search-icon-no-background-hd-260.png') }}" alt="Search" width=3% height=auto />
                            </div>    
                        </div>
                    <table id="thetable" class="table">
                        <thead>
                            <tr>
                                <th scope="col">Select</th>
                                <th scope="col">Name</th>
                                <th scope="col">G. Rođ.</th>
                                @if($layout == 'create/att_vale_event')
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
                        {{-- @foreach($valetudinarians as $valetudinarian) --}}
                            <tr>             <!-- $param == 1 is Vale check list (one raw of inputed Vale) with Events input -->
                                <!--td><input type="checkbox" @-if(($valetudinarian->used_by_other == 1) || ($layout == 'add')) disabled @-endif name="vale_selected[]" value="{--{ $valetudinarian->id }}"></td-->
                                <td><input type="checkbox" @if($valetudinarian->used_by_other == 1) disabled @endif @if($layout == 'create_vale_event' || $layout == 'create/att_vale_event') disabled checked @endif name="vale_selected[]" value="{{ $valetudinarian->id }}"></td>
                                <td>
                                    <a href="{{ url('/show/'.$valetudinarian->id) }}">
                                    {{ $valetudinarian->first_name }}
                                    {{ $valetudinarian->last_name }}
                                    </a>
                                </td>
                                <td>{{ strtok($valetudinarian->date_of_birth, '-') }}</td>
                                <td>{{ $valetudinarian->party->name }}</td>
                                <td>{{ $valetudinarian->location->name }}</td>
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

                            {{--@isset($valetudinarian->vev_description)--}}
                            <tr>
                                <th>Event Description<br>for {{ $valetudinarian->first_name }} {{ $valetudinarian->last_name }}
                                </th>
                                <td colspan="5">
                                    <div class="mb-3 item" data-help-title="Dodatni opis dogadjaja u vezi date osobe Help"
                                        data-help-text="Ovde mozete uneti dodatni opis dogadjaja vezanih za osobu koju trenutno unosite. Opis date osobe u svetlu izabranog dogadjaja. Mozete uneti i linkove na stranice, kao i copy/paste text iz drugih izvora. Pokusajte da jasno unesete sve podatke vezane za dogadaj koji se tice subjekta za koji je vezan.">
                                        <textarea id="vev_description" name="vev_description" cols="100" rows="5" class="form-control" maxlength="800" placeholder="Enter additional Event Description for {{ isset($valetudinarian->first_name, $valetudinarian->last_name) ? $valetudinarian->first_name . ' ' . $valetudinarian->last_name : 'this particular subject' }}"></textarea>
                                        {{-- WAS: placeholder="Enter additional Event Description for this particular subject"--}}
                                            <!-- Progress Bar Container -->
                                        <div class="progress mt-2" style="height: 10px;">
                                            <div id="description-bar" 
                                                class="progress-bar bg-success" 
                                                role="progressbar" 
                                                style="width: 0%;" 
                                                aria-valuenow="0" 
                                                aria-valuemin="0" 
                                                aria-valuemax="800">
                                            </div>
                                        </div>
                                        <small class="form-text text-muted">
                                            <!--span id="vev_char-count">800</span> characters remaining. -->
                                            <span id="vev_char-count">0</span> / 800 characters
                                        </small>
                                    </div>
                                </td>
                            </tr>
                            {{--@endisset--}}

                        {{-- @endforeach --}}
                        </tbody>
                    </table>
                    </div>

                </div> 