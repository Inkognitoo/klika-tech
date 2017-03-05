'use strict';

import React from 'react';

export default class Filter extends React.Component {
    constructor(props) {
        super(props);
        this.onChangeFilter = this.onChangeFilter.bind(this);
    }

    render() {
        let singers_select = null;
        let genres_select = null;
        let years_select = null;

        try {
            singers_select =
                <span className="filter-wrap">
                <label htmlFor="singer">Исполнитель</label>
                <select id="singer" defaultValue={this.props.filters.variables.singer} onChange={this.onChangeFilter}>
                    {this.props.filters.contents.singers.map((singer) => {
                        return <option key={singer.internal_name} value={singer.internal_name}>{singer.name}</option>
                    })}
                </select>
            </span>;
        } catch (err) {
            console.error(err);
        }

        try {
            genres_select =
                <span className="filter-wrap">
                <label htmlFor="genre">Жанр</label>
                <select id="genre" defaultValue={this.props.filters.variables.genre} onChange={this.onChangeFilter}>
                    {this.props.filters.contents.genres.map((genre) => {
                        return <option key={genre.internal_name} value={genre.internal_name}>{genre.name}</option>
                    })}
                </select>
            </span>;
        } catch (err) {
            console.error(err)
        }

        try {
            years_select =
                <span className="filter-wrap">
                <label htmlFor="year">Год</label>
                <select id="year" defaultValue={this.props.filters.variables.year} onChange={this.onChangeFilter}>
                    {this.props.filters.contents.years.map((year) => {
                        return <option key={year.internal_name} value={year.internal_name}>{year.name}</option>
                    })}
                </select>
            </span>;
        } catch (err) {
            console.error(err);
        }

        return (
            <aside className="block-filters">
                <h2>Фильтр</h2>
                <div className="filters-wrap">
                    <div className="filters">
                        {singers_select}
                        {genres_select}
                        {years_select}
                    </div>
                </div>
            </aside>
        );
    }

    onChangeFilter(e) {
        this.props.onChangeFilter(e.target.id, e.target.value);
    }
};

