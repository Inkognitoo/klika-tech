'use strict';

import React from 'react';

export default class Filter extends React.Component {
    constructor(props) {
        super(props);
    }

    render() {
        return (
            <aside className="block-filters">
                <h2>Фильтр</h2>
                <div className="filters-wrap">
                    <div className="filters">
                        <label htmlFor="singer">Исполнитель</label>
                        <select id="singer">
                            <option>Все</option>
                        </select>
                        <label htmlFor="genre">Жанр</label>
                        <select id="genre">
                            <option>Все</option>
                        </select>
                        <label htmlFor="year">Год</label>
                        <select id="year">
                            <option>Все</option>
                        </select>
                    </div>
                </div>
            </aside>
        );
    }
};

