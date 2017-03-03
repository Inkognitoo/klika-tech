'use strict';

import React from 'react';
import ReactDOM from 'react-dom';

import Playlist from './components/Playlist';
import Filter from './components/Filter';


class TrackCollection extends React.Component {
    constructor(props) {
        super(props);

        this.state = {
            tracks: [],
            sorts: [],
            filters: []
        }
    }

    render() {
        return (
            <article className="tracks-collection">
                <Playlist />
                <Filter />
            </article>
        );
    }
}

ReactDOM.render(
    <TrackCollection />,
    document.getElementById('root')
);