<div id="search" class="input-group">
  <span class="input-group-btn">
    <button type="button"
            id="btn_search"
            class="btn btn-default btn-lg"><i class="fa fa-search"></i></button>
  </span>
  <input type="text" name="search" id="inp_search" value="<?php echo $search; ?>"
         style="display: none"
         placeholder="<?php echo $text_search; ?>" class="form-control input-lg"
  />
</div>
<script>
  $(function () {
    $("#btn_search").hover( showSearch )
  })

  function showSearch() {
    $("#inp_search").toggle()
    $("#inp_search").focus()
  }
</script>