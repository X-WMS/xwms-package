<!-- Modal -->

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Show the modal when the document is fully loaded
        var modalElement = document.getElementById('{{ $id }}');
        if (modalElement) {
            var bootstrapModal = new bootstrap.Modal(modalElement);
            bootstrapModal.show();
        }
    });
  </script>
  
  
  <div class="modal fade modal-lg" id="{{ $id }}" tabindex="-1" role="dialog"
      aria-labelledby="{{ $id }}Title" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
              <div class="modal-header">
                  <h5 class="modal-title" id="{{ $id }}Title">User <span></span></h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                  </button>
              </div>
              <div class="modal-body">
                  <div class="row">
                      <div class="col-xl-5 grid-margin stretch-card">
                          <div class="card">
                              <div class="card-body">
                                  <div class="d-flex flex-column align-items-center position-relative col-12">
  
                                      <div class="position-relative">
                                          <img src="{{ $user['img'] }}"
                                              class="users-userImg rounded-pill">
                                          <span class="users-userImg-change p-1"><b class="mdi mdi-camera"></b></span>
                                      </div>
  
                                      <form class="forms-sample pt-4 row col-12">
  
                                          <div class="form-group col-xl-6 blue-form">
                                              <label for="UserDataFrontname">Front name</label>
                                              <input type="text" class="form-control" id="UserDataFrontname"
                                                  placeholder="{{ $user['first_name'] }}">
                                          </div>
  
                                          <div class="form-group col-xl-6 blue-form">
                                              <label for="UserDataLastname">Last name</label>
                                              <input type="text" class="form-control" id="UserDataLastname"
                                                  placeholder="{{ $user['last_name'] }}">
                                          </div>
  
                                          <div class="form-group blue-form">
                                              <label for="UserDataEmail">Email</label>
                                              <input type="email" class="form-control" id="UserDataEmail"
                                                  placeholder="{{ $user['email'] }}">
                                          </div>
  
                                          <div class="form-group">
                                              <label for="UserDataRole">Role</label>
                                              <select class="form-control" id="UserDataRole" name="role">
                                                  @foreach($user['roles'] as $roleName => $editable)
                                                      <option value="{{ $roleName }}" 
                                                              {{ $editable === 0 ? 'disabled' : '' }} 
                                                              {{ $user['role'] === $roleName ? 'selected' : '' }}>
                                                          {{ $roleName }}
                                                      </option>
                                                  @endforeach
                                              </select>
                                          </div>
                                        
  
                                          <div class="form-group blue-form">
                                              <label for="UserDataPassword">Password</label>
                                              <input type="password" class="form-control" id="UserDataPassword"
                                                  placeholder="Password">
                                          </div>
  
                                          <div class="form-group blue-form">
                                              <label for="UserDataPasswordRepeat">Repeat password</label>
                                              <input type="password" class="form-control" id="UserDataPasswordRepeat"
                                                  placeholder="Repeat password">
                                          </div>
  
                                          <div>
                                              <button type="submit" class="btn btn-gradient-primary me-2" data-id="{{ $user['id'] }}">Save</button>
                                              <button class="btn btn-dark" data-id="{{ $user['id'] }}">Cancel</button>
                                          </div>
  
                                      </form>
  
                                  </div>
                              </div>
                          </div>
                      </div>
  
  
  
                      <div class="col-xl-7 grid-margin stretch-card">
                        <div class="card">
                          <div class="card-body">
                            <h4 class="card-title"> Activity Overview for {{ $user['first_name'] }} {{ $user['last_name'] ?? '' }} on {{ config('app.name', 'XWMS Admin') }}</h4>
                            <div class="row">
                              <div class="table-sorter-wrapper col-lg-12 table-responsive">
                                <table id="user-listing" class="table">
                                    <thead>
                                        <tr>
                                            {{-- Generate table headers dynamically from the first item in the array --}}
                                            @if(!empty($user['activity']) && is_array($user['activity']))
                                                @foreach(array_keys($user['activity'][0]) as $key)
                                                    <th>{{ ucfirst($key) }}</th>
                                                @endforeach
                                            @else
                                                <th>No Data Available</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {{-- Generate table rows dynamically --}}
                                        @if(!empty($user['activity']) && is_array($user['activity']))
                                            @foreach($user['activity'] as $activity)
                                                <tr>
                                                    @foreach($activity as $value)
                                                        <td>{{ $value }}</td>
                                                    @endforeach
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="{{ !empty($user['activity'][0]) ? count($user['activity'][0]) : 1 }}">No activity data available</td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                  </div>
              </div>
              <div class="modal-footer">
                  <div class="position-relative d-flex flex-wrap justify-content-between col-12">
                      <div class="d-flex flex-row align-self-center">
                          <span>id <span class="text-muted">-</span> <b>{{ $user['id'] }}</b></span>
                      </div>
                      <div class="d-flex flex-row align-self-center">
                          <span>updated at <span class="text-muted">-</span> <b>{{ $user['updated_at'] }}</b></span>
                      </div>
                  </div>
              </div>
          </div>
      </div>
  </div>
  