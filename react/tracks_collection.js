'use strict';

import React from 'react';
import axios from 'axios';
import ReactDOM from 'react-dom';

import Playlist from './components/Playlist';
import Filter from './components/Filter';


class TrackCollection extends React.Component {
    constructor(props) {
        super(props);

        this.state = {
            tracks: [],
            sorts: [],
            filters: {
                contents: {
                    singers: [],
                    genres: [],
                    years: []
                },
                variables: {
                    singer: 'all',
                    genre: 'all',
                    year: 'all',
                }
            },
            pages: {
                count: 0,
                current: 1,
                tracks_count: 5
            }
        };

        this.getTracks(this.state)
            .then((new_state) => {
                this.setState(new_state);
            })
            .catch((err) => {
                console.error(err)
            });
    }

    render() {
        return (
            <article className="tracks-collection">
                <Playlist tracks={this.state.tracks}
                          pages={this.state.pages}
                          sorts={this.state.sorts}
                          onClickPage={this.onClickPage.bind(this)}
                          onClickTracksCount={this.onClickTracksCount.bind(this)}
                          onClickSort={this.onClickSort.bind(this)}
                />
                <Filter filters={this.state.filters}
                        onChangeFilter={this.onChangeFilter.bind(this)}
                />
            </article>
        );
    }

    onClickPage(current_page) {
        if (current_page == 'minus') {
            current_page = (this.state.pages.current> 1) ? this.state.pages.current - 1 : this.state.pages.current;
        }
        if (current_page == 'plus') {
            current_page = (this.state.pages.current < this.state.pages.count) ? this.state.pages.current + 1 : this.state.pages.current;
        }
        let payload = {...this.state, pages: {...this.state.pages, current: current_page}};

        this.getTracks(payload)
            .then((new_state) => {
                this.setState(new_state);
            })
            .catch((err) => {
                console.error(err)
            });
    }

    onClickTracksCount(tracks_count) {
        let payload = {...this.state, pages: {...this.state.pages, tracks_count: tracks_count}};

        this.getTracks(payload)
            .then((new_state) => {
                this.setState(new_state);
            })
            .catch((err) => {
                console.error(err)
            });
    }

    onChangeFilter(type, value) {
        let filter = {};
        Object.defineProperty(filter, type, {
            value: value,
            configurable: true,
            writable: true,
            enumerable: true
        });

        let payload = {...this.state, filters: {
            ...this.state.filters, ...{variables: {
                ...this.state.filters.variables, ...filter }
            }
        }};

        this.getTracks(payload)
            .then((new_state) => {
                this.setState(new_state);
            })
            .catch((err) => {
                console.error(err)
            });
    }

    onClickSort(name, type) {
        let sorts = [];
        if (type == null) {
            sorts = this.state.sorts.map((sort) => {
                return (sort.internal_name == name) ? null : sort;
            });
        } else {
            let is_update = false;
            sorts = this.state.sorts.map((sort) => {
                if(sort.internal_name == name) {
                    is_update = true;
                    sort.type = type;
                }
                return sort;
            });

            if (!is_update) {
                sorts.push({internal_name: name, type: type});
            }
        }

        let payload = {...this.state, sorts: sorts};
        this.getTracks(payload)
            .then((new_state) => {
                this.setState(new_state);
            })
            .catch((err) => {
                console.error(err)
            });
    }

    getTracks(payload) {
        let filters = [];
        for (let name in payload.filters.variables) {
            filters.push({name: name, value: payload.filters.variables[name]});
        }

        console.log('payload', payload);
        console.log('filters', filters);
        return axios.get('/api/tracks', {
            params: {
                payload: {
                    pages: payload.pages,
                    filters: filters,
                    sorts: payload.sorts
                }
        }})
            .then((response) => {
                console.log('new_state', {...this.state, ...response.data});
                return {...this.state, ...response.data};
            })
            .catch((error) => {
                console.error(error);
                return this.state;
            });

    }
}

ReactDOM.render(
    <TrackCollection />,
    document.getElementById('root')
);