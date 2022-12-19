<div class="row">
    <div class="col-md-12">
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th class="vertical-center">#</th>
                    <th class="vertical-center">@lang('label.CATEGORY')</th>
                    <th class="vertical-center">@lang('label.PRODUCT_NAME')</th>
                    <th class="vertical-center text-center">@lang('label.PRODUCT_CODE')</th>
                </tr>
            </thead>
            <tbody>
                @if (!$productArr->isEmpty())
                @foreach($productArr as $product)
                <?php
                $checked = '';
                $disabled = 'disabled';
                $productId = $product->id;
                if (!empty($previousDataArr)) {
                    $targetArr = array_filter($previousDataArr, function($elem) use($productId) {
                        return $elem['product_id'] === $productId;
                    });
                    $existsDataArr = reset($targetArr);

                    if (!empty($existsDataArr)) {
                        $checked = 'checked="checked"';
                        $disabled = '';
                    } else {
                        $disabled = 'disabled';
                        $checked = '';
                    }
                }
                ?>

                <tr>
                    <td class="vertical-center">
                        <div class="md-checkbox">
                            {!! Form::checkbox('product_id['.$product->id.']', $product->id, $checked, ['id' => $product->id, 'class'=> 'md-check']) !!}
                            {!! Form::hidden('product_name['.$product->id.']', $product->name) !!}
                            <label for="{{$product->id}}">
                                <span class="inc"></span>
                                <span class="check"></span>
                                <span class="box"></span> 
                            </label>
                        </div>
                    </td>
                    <td class="vertical-center"> {{ $product->category_name }} </td>
                    <td class="vertical-center"> {{ $product->name}} </td>
                    <td class="text-center vertical-center">{{ $product->product_code }}</td>
                </tr>
                @endforeach
                @else
                <tr>
                    <td colspan="8">@lang('label.NO_PRODUCT_FOUND')</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>

<div class="form-actions">
    <div class="row">
        <div class="col-md-offset-4 col-md-8">
            @if(!empty($userAccessArr[21][15]))
            <button class="btn btn-circle green btn-submit" type="button">
                <i class="fa fa-check"></i> @lang('label.SUBMIT')
            </button>
            @endif
            @if(!empty($userAccessArr[21][1]))
            <a href="{{ URL::to('/productToSupplier') }}" class="btn btn-circle btn-outline grey-salsa">@lang('label.CANCEL')</a>
            @endif
        </div>
    </div>
</div>