<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-body">
        <div class="d-md-flex align-items-center">
          <div>
            <h4 class="card-title">Subscribers</h4>
            <p class="card-subtitle">
              <?= SITE_NAME ?> subscribers list with details.
            </p>
          </div>
          <div class="ms-auto mt-3 mt-md-0">
            <select id="selSubscriptionType"
                    class="form-select theme-select border-0"
                    aria-label="Default select example">
              <option id="optAll" value="All">All</option>
              <option id="optEmail" value="Email">Email</option>
              <option id="optTel" value="Teléfono">Teléfono</option>
            </select>
          </div>
        </div>
        <div class="table-responsive mt-4">
          <table id="subscribers_list" class="table mb-0 text-nowrap align-middle fs-3">
            <thead>
              <tr>
                <th scope="col" class="px-0 text-muted">Address</th>
                <th scope="col" class="px-0 text-muted">Type</th>
                <th scope="col" class="px-0 text-muted">Origin</th>
                <th scope="col" class="px-0 text-muted">GeoLocation</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
