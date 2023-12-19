/* Requires the Docker Pipeline plugin */
pipeline {
    agent any
    stage('SonarQube analysis') {
        def scannerHome = tool 'SonarScanner 4.0';
            withSonarQubeEnv('My SonarQube Server') { // If you have configured more than one global server connection, you can specify its name
                sh "${scannerHome}/bin/sonar-scanner"
        }
    }
}
