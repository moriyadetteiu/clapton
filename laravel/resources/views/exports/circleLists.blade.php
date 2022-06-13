<table>
    <thead>
        <tr>
            @foreach($columns as $column)
                <th>{{ $column }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach($aggregatedCircleLists as $circleLists)
            @foreach($circleLists as $index => $circleList)
                <tr>
                    @foreach($columns as $name => $label)
                        @php
                            $isAggregateColumn = in_array($name, [
                                'circle_placement_classification_name',
                                'placement_full',
                                'circle_name'
                            ])
                        @endphp
                        @if($isAggregateColumn)
                            @if ($index === 0)
                                <td rowspan="{{ $circleLists->count() }}">{{ $circleList->$name }}</td>
                            @endif
                        @else
                            <td>{{ $circleList->$name }}</td>
                        @endif
                    @endforeach
                </tr>
            @endforeach
        @endforeach
    </tbody>
</table>
