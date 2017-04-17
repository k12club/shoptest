@if ($user->cards()->count() > 0)
    <table class="table table-striped table-bordered table-hover" id="payment-current-cards">
        <thead>
        <tr>
            <th>
                Credit Cards
            </th>
        </tr>
        </thead>
        <tbody>
        @foreach ($user->cards as $card)
            <tr>
                <td>
                    <span class="pull-left">
                        {!! CustomHelper::formatCard($card) !!}
                    </span>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@else
    <div class="alert alert-warning">
        This user has no saved credit cards at the present.
    </div>
@endif