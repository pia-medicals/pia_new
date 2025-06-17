

<div class="dashboard_body content-wrapper">
    <section class="content">
        <?php $this->alert(); ?>
        <div class="box box-primary">
            <div class="box-header with-border">
                <h2 class="box-title">Add</h2>
            </div>

            <form role="Form" method="post" action="" class="admin_form" accept-charset="UTF-8" autocomplete="off">

                <?php
                $analysis_category = $this->Admindb->table_full('analyses_category');
                ?>

                <div class="form-group">
                    <label for="name">Analysis Name</label>
                    <input type="text" id="name" class="form-control" required name="name" placeholder="Analysis Name" >
                </div>

                <div class="form-group">
                    <label for="category">Analysis Category</label>
                    <select name="category" id="category" class="form-control"  data-rule-required="true" >
                        <option selected disabled>Choose Category</option>
                        <?php
                        foreach ($analysis_category as $key => $value) {

                            echo '<option value="' . $value['category_id'] . '" ' . $sel . ' >' . $value['category_name'] . '</option>';
                        }
                        ?>
                    </select>

                </div>

                <div class="form-group">
                    <label for="part_number">Part Number</label>
                    <input type="number" id="part_number" class="form-control" required name="part_number" maxlength="4" placeholder="Part Number">
                </div>

                <div class="form-group">
                    <label for="price">Price </label>
                    <input type="number" id="price" class="form-control" required name="price" placeholder="Price" >
                </div>

                <div class="form-group">
                    <label for="minimum_time">Minimum Time</label>
                    <input type="number" id="minimum_time" class="form-control" required name="minimum_time" maxlength="5" placeholder="Minimum Time">
                </div>

                <!--                <div class="form-group">
                                    <label for="discription">Description</label>
                                    <textarea placeholder="Enter Description" id="discription" class="form-control" required name="discription"> </textarea>
                                </div>-->

                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea placeholder="Enter Description" id="description" class="form-control" required name="description"> </textarea>
                </div>

                <div class="form-group ">
                    <button type="submit" class="btn btn-primary btn-flat " id="submitbtn" name="submit">Add</button>
                </div>
            </form>
        </div>
    </section>
</div>