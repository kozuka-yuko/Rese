<div class="modal__update" id="modal__update">
    <a href="#" class="close__update" title="閉じる">&times;</a>
    <form action="{{ route('reservation.update', $reservation->id) }}" method="post" class="reservation-form">
        @method('PATCH')
        @csrf
        <table class="reservation__table">
            <tr class="table__row">
                <td class="reservation__data">Shop</td>
                <td class="reservation__data">{{ $reservation->shop->name }}</td>
            </tr>
            <tr class="table__row">
                <td class="reservation__data">Date</td>
                <td class="reservation__data">
                    <input type="date" name="date" class="reservation__date" value="{{ old('date', $reservation->date) }}" min="{{ $today }}">
                </td>
            </tr>
            <tr class="form__error">
                @error('date')
                {{ $message }}
                @enderror
            </tr>
            <tr class="table__row">
                <td class="reservation__data">Time</td>
                <td class="reservation__data">
                    <select name="time" class="reservation__time">
                        <option value="" hidden>time</option>
                        @foreach ($times as $time)
                        <option value="{{ $time }}">{{ $time }}</option>
                        @endforeach
                    </select>
                </td>
            </tr>
            <tr class="form__error">
                @error('time')
                {{ $message }}
                @enderror
            </tr>
            <tr class="table__row">
                <td class="reservation__data">Number</td>
                <td class="reservation__data">
                    <select name="number" class="number__inner">
                        <option value="" hidden>number of person</option>
                        @foreach ($numbers as $number)
                        <option value="{{ $number }}">{{ $number }}人</option>
                        @endforeach
                    </select>
                </td>
            </tr>
            <tr class="form__error">
                @error('number')
                {{ $message }}
                @enderror
            </tr>
        </table>
        <div class="button-submit">
            <div class="reservation__button">
                <button class="update__btn" type="submit">変更する</button>
            </div>
        </div>
    </form>
</div>