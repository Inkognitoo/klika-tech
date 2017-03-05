'use strict';

import React from 'react';

export default class Track extends React.Component {
    constructor(props) {
        super(props);
    }

    render() {
        let track = null;
        try {
            track =
                <tr>
                    <td>{this.props.track.name}</td>
                    <td>{this.props.track.singer}</td>
                    <td>{this.props.track.genre}</td>
                    <td>{this.props.track.year}</td>
                </tr>
        } catch (err) {
            console.error(err)
        }

        if (track) {
            return track;
        } else {
            return null;
        }
    }
};

