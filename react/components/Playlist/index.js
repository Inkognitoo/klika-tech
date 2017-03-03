'use strict';

import React from 'react';
import Track from '../Track';

export default class Playlist extends React.Component {
    constructor(props) {
        super(props);
    }

    render() {
        return (
            <section className="block-tracks">
                <header>Плейлист</header>
                <div className="tracks-wrap">
                    <table>
                        <thead>
                        <tr>
                            <td>
                                Исполнитель
                                <div className="arrows">
                                    <span className="arrow-up"></span>
                                    <span className="arrow-down"></span>
                                </div>
                            </td>
                            <td>
                                Песня
                                <div className="arrows">
                                    <span className="arrow-up"></span>
                                    <span className="arrow-down"></span>
                                </div>
                            </td>
                            <td>
                                Жанр
                                <div className="arrows">
                                    <span className="arrow-up"></span>
                                    <span className="arrow-down"></span>
                                </div>
                            </td>
                            <td>
                                Год
                                <div className="arrows">
                                    <span className="arrow-up"></span>
                                    <span className="arrow-down"></span>
                                </div>
                            </td>
                        </tr>
                        </thead>
                        <tbody>
                        <Track />
                        </tbody>
                    </table>
                </div>
                <footer>
                    <nav>
                        [ 1 2 3 4 ]
                    </nav>
                    <ul>
                        <li>5</li>
                        <li>10</li>
                        <li>15</li>
                        <li>20</li>
                    </ul>
                </footer>
            </section>
        );
    }
};
