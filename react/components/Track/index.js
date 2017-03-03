'use strict';

import React from 'react';

export default class Track extends React.Component {
    constructor(props) {
        super(props);
    }

    render() {
        return (
            <tr>
                <td>The Kingston Trio</td>
                <td>Tom Dooley</td>
                <td>Folk</td>
                <td>1958</td>
            </tr>
        );
    }
};

