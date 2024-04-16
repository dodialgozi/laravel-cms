<div class="px-3 mt-4">
    <form action="{{ url('artikel') }}" method="get">
        <div class="input-group mb-3 pb-1">
            <input class="form-control box-shadow-none text-1 border-0 bg-color-grey"
                placeholder="Cari Artikel..." name="cari" id="cari" type="text">
            <button type="submit" class="btn bg-color-grey text-1 p-2"><i
                    class="fas fa-search m-2"></i></button>
        </div>
    </form>
</div>