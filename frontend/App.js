import React, {Component} from 'react'
import {Tab, Tabs, TabList, TabPanel} from 'react-tabs'
import Select from 'react-select'
import 'font-awesome/css/font-awesome.css'

import './App.css';
import 'bootstrap/dist/css/bootstrap.css'
import 'react-tabs/style/react-tabs.css'
import 'react-select/dist/react-select.css'

import TagsInput from 'react-tagsinput'
import 'react-tagsinput/react-tagsinput.css'

import CodeMirror from 'react-codemirror'
import 'codemirror/lib/codemirror.css'
import 'codemirror/mode/haskell/haskell'
import 'codemirror/mode/clike/clike'

import axios from 'axios';

class App extends Component {

    constructor(props) {
        super(props);
        this.state = {
            exercises: window.config.exercises.concat(),
            tutorials: window.config.tutorials.concat(),
            tutorial: null,
            matriculations: [],
            newFileNames: window.config.exercises.map((e) => ""),
            warnings: [],
        };
    }

    handleCodeChange(exerciseIndex, fileIndex, newCode) {
        const updatedFile = Object.assign({}, this.state.exercises[exerciseIndex].files[fileIndex]);
        updatedFile.code = newCode;
        const updatedExercise = Object.assign({}, this.state.exercises[exerciseIndex]);
        updatedExercise.files[fileIndex] = updatedFile;
        const updatedExercises = this.state.exercises.concat();
        updatedExercises[exerciseIndex] = updatedExercise;
        this.setState({exercises: updatedExercises});
    }

    validate() {
        const warnings = [];
        if (!this.state.tutorial) {
            warnings.push("Sie müssen ein Tutorium auswählen, bei welchem Sie abgeben wollen!");
        }
        if (this.state.matriculations.length == 0) {
            warnings.push("Sie müssen mindestens eine Matrikelnummer angeben! Stellen Sie sicher, dass Sie jede Matrikelnummer mit Enter bestätigen!");
        }
        if(warnings.length > 0) {
            this.setState({warnings});
            return false;
        } else {
            return true;
        }
    }

    handleSubmitClicked() {
        if(!this.validate()) {
            return;
        }
        const data = {
            tutorial: parseInt(this.state.tutorial.value),
            matriculations: this.state.matriculations,
            exercises: this.state.exercises.map((exercise) => {
                return {
                    id: exercise.id,
                    files: exercise.files.map((file) => {
                        return {
                            'name': file.name,
                            'code': file.code
                        };
                    })
                };
            })
        };
        axios.post('submit.php', data)
            .then((response) => this.handleSuccessfulSubmission(response))
            .catch((error) => this.handleFailedSubmission(error));
    }

    handleSuccessfulSubmission() {
        alert("Abgabe war erfolgreich! Sie können jederzeit wieder abgeben. Ihr Tutor wird dann die letzte Abgabe bewerten.");
        window.location.reload();
    }

    handleFailedSubmission(error) {
        alert("Leider hat die Abgabe nicht funktioniert." +
            " Bitte schicken Sie ihrem Tutor die Abgabe per Mail. Sie können es auch später wieder versuchen.");
    }

    determineModeFromFilename(filename) {
        let extension = null;
        const dotIndex = filename.lastIndexOf(".");
        if (dotIndex >= 0) {
            extension = filename.substring(dotIndex);
        }
        const lookup = {
            ".java": "text/x-java",
            ".hs": "haskell",
        };
        return lookup[extension];
    }

    handleAddFile(exerciseIndex) {
        const newFilename = this.state.newFileNames[exerciseIndex];
        const newFile = {
            "name": newFilename,
            "isGiven": false,
            "mode": this.determineModeFromFilename(newFilename) || "",
            "code": "// Paste your code here."
        };
        const updatedExercise = Object.assign({}, this.state.exercises[exerciseIndex]);
        updatedExercise.files = updatedExercise.files.concat(newFile);
        const updatedExercises = this.state.exercises.concat();
        updatedExercises[exerciseIndex] = updatedExercise;
        const newFileNames = this.state.newFileNames.concat();
        newFileNames[exerciseIndex] = "";
        this.setState({exercises: updatedExercises, newFileNames});
    }

    render() {
        const selectOptions = this.state.tutorials.map((tutorial) => {
            return {value: tutorial.id, label: tutorial.description}
        });
        return (
            <div className="container-fluid">
                <header className="mt-4">
                    <h1>Abgabe Programmierung</h1>
                    <p className="text-muted">Vorlesung WS 2017/18 an der RWTH Aachen bei Prof. Giesl</p>
                    <hr />
                </header>

                {this.state.warnings.length > 0 ?
                    <div className="row">
                        <div className="col-md-12">
                            {this.state.warnings.map((warning) =>
                                <div className="alert alert-danger" role="alert">
                                    {warning}
                                </div>
                            )}
                        </div>
                    </div>
                : null }

                <div className="row">
                    <div className="col-md-3">
                        <div className="card">
                            <div className="card-body">
                                <p className="card-text">Bitte wählen Sie ihr Tutorium!</p>
                                <Select name="choose-tutor"
                                        options={selectOptions}
                                        placeholder="Tutorium auswählen.."
                                        value={this.state.tutorial}
                                        onChange={(tutorial) => this.setState({tutorial})}/>
                            </div>
                        </div>
                        <div className="card mt-2">
                            <div className="card-body">
                                <p className="card-text">Bitte geben Sie ihre eigene Matrikelnummer sowie die ihrer
                                    Abgabepartner an, jeweils gefolgt von <code>Enter</code>.</p>
                                <TagsInput
                                    value={this.state.matriculations}
                                    onlyUnique={true}
                                    validationRegex={/^[0-9]{6}$/}
                                    onValidationReject={() => alert("Das ist keine gültige Matrikelnummer!")}
                                    onChange={(matriculations) => this.setState({matriculations})}
                                    inputProps={{placeholder: 'Matrikelnr.'}}/>
                            </div>
                        </div>
                        <button
                            type="button"
                            className="btn btn-success btn-lg btn-block mt-2 mb-4"
                            onClick={this.handleSubmitClicked.bind(this)}>
                                Lösung abgeben
                        </button>
                    </div>
                    <div className="col-md-9">
                        {this.state.exercises.map((exercise, exerciseIndex) =>
                            <div className="card mb-4">
                                <div className="card-header">{exercise.name}</div>
                                <div className="card-body">
                                    <Tabs>
                                        <TabList>
                                            {exercise.files.map((file) =>
                                                <Tab>{file.name}</Tab>
                                            )}
                                        </TabList>

                                        {exercise.files.map((file, fileIndex) =>
                                            <TabPanel>
                                                {file.isGiven ? <p>Diese Datei wird Ihnen gestellt. Sie können Sie <b>nicht
                                                    verändern.</b></p> : null}
                                                <CodeMirror
                                                    value={file.code}
                                                    onChange={this.handleCodeChange.bind(this, exerciseIndex, fileIndex)}
                                                    options={{
                                                        mode: file.mode,
                                                        lineNumbers: true,
                                                        readOnly: file.isGiven
                                                    }}/>
                                            </TabPanel>
                                        )}
                                    </Tabs>
                                    <div className="mt-4">
                                        Wenn Sie weitere Dateien abgeben möchten, dann können Sie das hier tun:
                                        <div className="input-group mt-2">
                                            <input
                                                type="text"
                                                className="form-control"
                                                placeholder="Dateiname.."
                                                value={this.state.newFileNames[exerciseIndex]}
                                                onChange={(c) => {
                                                    const updatedFilenames = this.state.newFileNames.concat();
                                                    updatedFilenames[exerciseIndex] = c.target.value;
                                                    this.setState({newFileNames: updatedFilenames});
                                                }}/>
                                            <span className="input-group-btn">
                                                <button type="button" className="btn btn-info" onClick={this.handleAddFile.bind(this, exerciseIndex)}>
                                                    <i className="fa fa-plus mr-2" />
                                                    Datei anlegen
                                                </button>
                                            </span>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            ) /* exercise */}
                    </div>
                </div>
                <div className="row">
                    <div className="col-md-12">
                        <p className="text-muted text-center">
                            <a href="https://github.com/0xfacade/programming-grader">programming-grader</a> by Florian M. Behrens, &copy; 2017
                        </p>
                    </div>
                </div>
            </div>
        );
    }
}

export default App;
