@include('layouts.header')
@include('layouts.navbar')
<div class="row">
    @include('layouts.sidebar')
    <div class="col-md-9">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible">
            {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible">
            {{ session('error') }}
            </div>
        @endif

        <div class="table-responsive">
            <div class="row">
                <div class="col-md-3">
                    <label class="form-label">Keywords</label>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Start Date</label>
                </div>
                <div class="col-md-3">
                    <label class="form-label">End Date</label>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Type of Report</label>
                </div>
            </div>
            
            <form>
                <div class="row">
                    <div class="col-md-3">
                        <input type="text" class="form-control" id="searchinputitem" placeholder="Type keywords">
                    </div>
                    <div class="col-md-3">
                        <input type="datetime-local" class="form-control" id="startdate" name="startdate">
                    </div>
                    <div class="col-md-3">
                        <input type="datetime-local" class="form-control" id="enddate" name="enddate">
                    </div>
                    <div class="col-md-3">
                        <select class="form-select" id="reporttype" name="reporttype">
                            <option value="Daily Report">Daily Report</option>
                            <option value="Sales Transactions">Sales Transactions</option>
                            <option value="Sold Items : Detailed">Sold Items : Detailed</option>
                            <option value="Sold Items : Summarized">Sold Items : Summarized</option>
                        </select>
                    </div>
                </div>
            </form>
            <div class="row">
                <div class="col-md-6" id="reportbuttonsdiv">
                    <button type="button" class="btn btn-primary btn-sm generate" id="generate" name="generate">Generate</button>
                </div>
            </div>
            <br>
            <hr>
            <div class="row">
                <div class="reportsbody" id="reportsbody" name="reportsbody">
                    @include('reports.daily')
                    {{-- @include('reports.solditems') --}}
                </div>
            </div>
        </div>
    </div>
</div>

@include('layouts.footer')
@include('reports.cashonhand')

