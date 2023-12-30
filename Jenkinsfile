/* Requires the Docker Pipeline plugin */
pipeline {
    agent any 
    stages {
        stage('build') {
            steps {
                echo 'Hello World'
            }
        }
        stage('SonarQubeScanner'){
            steps{
                script {def scannerHome = tool 'SonarScanner 2.16.1';}
                withSonarQubeENv('thomas'){
                    sh "${scannerHome}/bin/sonar-scanner"
                }
            }
        }
    }
}