'use strict';

import React from 'react';

export default class Track extends React.Component {
    constructor(props) {
        super(props);
    }

    render() {
        return (
            <tr>
                <td>{this.props.track.name}</td>
                <td>{this.props.track.singer}</td>
                <td>{this.props.track.genre}</td>
                <td>{this.props.track.year}</td>
            </tr>
        );
    }
};

