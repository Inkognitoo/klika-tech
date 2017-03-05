'use strict';

import React from 'react';
import Track from '../Track';

export default class Playlist extends React.Component {
    constructor(props) {
        super(props);

        this.columns = [
            {
                internal_name: 'name',
                name: 'Песня',
                sort: null
            },
            {
                internal_name: 'singer',
                name: 'Исполнитель',
                sort: null
            },
            {
                internal_name: 'genre',
                name: 'Жанр',
                sort: null
            },
            {
                internal_name: 'year',
                name: 'Год',
                sort: null
            }
        ];

        this.tracks_count = [5, 10, 15, 20];

        this.onClickTracksCount = this.onClickTracksCount.bind(this);
        this.onClickPage = this.onClickPage.bind(this);
        this.onClickSort = this.onClickSort.bind(this);
    }

    render() {
        let columns = new Array(...this.columns.map((column) => {return {...column}}));
        let pages = null;
        let tracks_count = null;

        try {
            columns = columns.map((column) => {
                let sort = this.props.sorts.find((sort) => { return column.internal_name == sort.internal_name});
                if (sort) {
                    column.sort = sort.type
                }
                return column;
            });
        } catch (err) {
            console.error(err)
        }

        try {
            pages = Array.apply(null, new Array(this.props.pages.count))
                .map((x, i) => {
                    return <span key={i}
                                 data-value={i + 1}
                                 onClick={(this.props.pages.current == i + 1) ? null : this.onClickPage}
                                 className={(this.props.pages.current == i + 1) ? 'active-page' : null}>{i + 1}</span>;
                });
        } catch (err) {
            console.error(err);
        }

        try {
            tracks_count = this.tracks_count.map((count) => {
                return <li key={count}
                           value={count}
                           onClick={this.onClickTracksCount}
                           className={(this.props.pages.tracks_count == count) ? 'active-count' : null}>{count}</li>

            })
        } catch (err) {
            console.error(err)
        }

        return (
            <section className="block-tracks">
                <header>Плейлист</header>
                <div className="tracks-wrap">
                    <table>
                        <thead>
                        <tr>
                            {columns.map((column, i) => {
                                return (
                                    <td key={i}>
                                        {column.name}
                                        <div className="arrows">
                                            <span
                                                data-name={column.internal_name}
                                                data-type="asc"
                                                data-active={(column.sort == 'asc')}
                                                onClick={this.onClickSort}
                                                className={`arrow-up ${(column.sort == 'asc') ? "active-arrow-up" : ""}`}></span>
                                            <span
                                                data-name={column.internal_name}
                                                data-type="desc"
                                                data-active={(column.sort == 'desc')}
                                                onClick={this.onClickSort}
                                                className={`arrow-down ${(column.sort == 'desc') ? "active-arrow-down" : ""}`}></span>
                                        </div>
                                    </td>);
                            })}
                        </tr>
                        </thead>
                        <tbody>
                            {this.props.tracks.map((track) => {
                                return <Track key={track.id} track={track} />
                            })}
                        </tbody>
                    </table>
                </div>
                <footer>
                    <nav>
                        <span data-value="minus" onClick={(this.props.pages.current > 1) ? this.onClickPage : null}>[</span>
                        {pages}
                        <span data-value="plus" onClick={(this.props.pages.current < this.props.pages.count) ? this.onClickPage : null}>]</span>
                    </nav>
                    <ul>
                        {tracks_count}
                    </ul>
                </footer>
            </section>
        );
    }

    onClickSort(e) {
        this.props.onClickSort(e.target.getAttribute("data-name"),
            ((e.target.getAttribute("data-active") == "true")? null : e.target.getAttribute("data-type")));
    }

    onClickPage(e) {
        this.props.onClickPage(e.target.getAttribute("data-value"))
    }

    onClickTracksCount(e) {
        this.props.onClickTracksCount(e.target.value)
    }
};
