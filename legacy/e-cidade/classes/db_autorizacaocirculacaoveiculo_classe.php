<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBSeller Servicos de Informatica
 *                            www.dbseller.com.br
 *                         e-cidade@dbseller.com.br
 *
 *  Este programa e software livre; voce pode redistribui-lo e/ou
 *  modifica-lo sob os termos da Licenca Publica Geral GNU, conforme
 *  publicada pela Free Software Foundation; tanto a versao 2 da
 *  Licenca como (a seu criterio) qualquer versao mais nova.
 *
 *  Este programa e distribuido na expectativa de ser util, mas SEM
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM
 *  PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais
 *  detalhes.
 *
 *  Voce deve ter recebido uma copia da Licenca Publica Geral GNU
 *  junto com este programa; se nao, escreva para a Free Software
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *  02111-1307, USA.
 *
 *  Copia da licenca no diretorio licenca/licenca_en.txt
 *                                licenca/licenca_pt.txt
 */

//MODULO: veiculos
//CLASSE DA ENTIDADE autorizacaocirculacaoveiculo
class cl_autorizacaocirculacaoveiculo { 
   // cria variaveis de erro 
   var $rotulo     = null; 
   var $query_sql  = null; 
   var $numrows    = 0; 
   var $numrows_incluir = 0; 
   var $numrows_alterar = 0; 
   var $numrows_excluir = 0; 
   var $erro_status= null; 
   var $erro_sql   = null; 
   var $erro_banco = null;  
   var $erro_msg   = null;  
   var $erro_campo = null;  
   var $pagina_retorno = null; 
   // cria variaveis do arquivo 
   var $ve13_sequencial = 0; 
   var $ve13_instituicao = 0; 
   var $ve13_veiculo = 0; 
   var $ve13_motorista = 0; 
   var $ve13_datainicial_dia = null; 
   var $ve13_datainicial_mes = null; 
   var $ve13_datainicial_ano = null; 
   var $ve13_datainicial = null; 
   var $ve13_datafinal_dia = null; 
   var $ve13_datafinal_mes = null; 
   var $ve13_datafinal_ano = null; 
   var $ve13_datafinal = null; 
   var $ve13_dataemissao_dia = null; 
   var $ve13_dataemissao_mes = null; 
   var $ve13_dataemissao_ano = null; 
   var $ve13_dataemissao = null; 
   var $ve13_observacao = null;
   var $ve13_departamento = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ve13_sequencial = int4 = Código 
                 ve13_instituicao = int4 = Instituição 
                 ve13_veiculo = int4 = Veículo 
                 ve13_motorista = int4 = Motorista 
                 ve13_datainicial = date = Data Inicial 
                 ve13_datafinal = date = Data Final 
                 ve13_dataemissao = date = Data de Emissão 
                 ve13_observacao = text = Observação 
                 ve13_departamento = int4 = Departamento 
                 ";
   //funcao construtor da classe 
   function cl_autorizacaocirculacaoveiculo() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("autorizacaocirculacaoveiculo"); 
     $this->pagina_retorno =  basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]);
   }
   //funcao erro 
   function erro($mostra,$retorna) { 
     if(($this->erro_status == "0") || ($mostra == true && $this->erro_status != null )){
        echo "<script>alert(\"".$this->erro_msg."\");</script>";
        if($retorna==true){
           echo "<script>location.href='".$this->pagina_retorno."'</script>";
        }
     }
   }
   // funcao para atualizar campos
   function atualizacampos($exclusao=false) {
     if($exclusao==false){
       $this->ve13_sequencial = ($this->ve13_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ve13_sequencial"]:$this->ve13_sequencial);
       $this->ve13_instituicao = ($this->ve13_instituicao == ""?@$GLOBALS["HTTP_POST_VARS"]["ve13_instituicao"]:$this->ve13_instituicao);
       $this->ve13_veiculo = ($this->ve13_veiculo == ""?@$GLOBALS["HTTP_POST_VARS"]["ve13_veiculo"]:$this->ve13_veiculo);
       $this->ve13_motorista = ($this->ve13_motorista == ""?@$GLOBALS["HTTP_POST_VARS"]["ve13_motorista"]:$this->ve13_motorista);
       if($this->ve13_datainicial == ""){
         $this->ve13_datainicial_dia = ($this->ve13_datainicial_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ve13_datainicial_dia"]:$this->ve13_datainicial_dia);
         $this->ve13_datainicial_mes = ($this->ve13_datainicial_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ve13_datainicial_mes"]:$this->ve13_datainicial_mes);
         $this->ve13_datainicial_ano = ($this->ve13_datainicial_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ve13_datainicial_ano"]:$this->ve13_datainicial_ano);
         if($this->ve13_datainicial_dia != ""){
            $this->ve13_datainicial = $this->ve13_datainicial_ano."-".$this->ve13_datainicial_mes."-".$this->ve13_datainicial_dia;
         }
       }
       if($this->ve13_datafinal == ""){
         $this->ve13_datafinal_dia = ($this->ve13_datafinal_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ve13_datafinal_dia"]:$this->ve13_datafinal_dia);
         $this->ve13_datafinal_mes = ($this->ve13_datafinal_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ve13_datafinal_mes"]:$this->ve13_datafinal_mes);
         $this->ve13_datafinal_ano = ($this->ve13_datafinal_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ve13_datafinal_ano"]:$this->ve13_datafinal_ano);
         if($this->ve13_datafinal_dia != ""){
            $this->ve13_datafinal = $this->ve13_datafinal_ano."-".$this->ve13_datafinal_mes."-".$this->ve13_datafinal_dia;
         }
       }
       if($this->ve13_dataemissao == ""){
         $this->ve13_dataemissao_dia = ($this->ve13_dataemissao_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ve13_dataemissao_dia"]:$this->ve13_dataemissao_dia);
         $this->ve13_dataemissao_mes = ($this->ve13_dataemissao_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ve13_dataemissao_mes"]:$this->ve13_dataemissao_mes);
         $this->ve13_dataemissao_ano = ($this->ve13_dataemissao_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ve13_dataemissao_ano"]:$this->ve13_dataemissao_ano);
         if($this->ve13_dataemissao_dia != ""){
            $this->ve13_dataemissao = $this->ve13_dataemissao_ano."-".$this->ve13_dataemissao_mes."-".$this->ve13_dataemissao_dia;
         }
       }
       $this->ve13_observacao = ($this->ve13_observacao === null?@$GLOBALS["HTTP_POST_VARS"]["ve13_observacao"]:$this->ve13_observacao);
       $this->ve13_departamento = ($this->ve13_departamento == ""?@$GLOBALS["HTTP_POST_VARS"]["ve13_departamento"]:$this->ve13_departamento);
     }else{
       $this->ve13_sequencial = ($this->ve13_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ve13_sequencial"]:$this->ve13_sequencial);
     }
   }
   // funcao para Inclusão
   function incluir ($ve13_sequencial = null){
      $this->atualizacampos();
     if($this->ve13_instituicao == null ){ 
       $this->erro_sql = " Campo Instituição não informado.";
       $this->erro_campo = "ve13_instituicao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ve13_veiculo == null ){ 
       $this->erro_sql = " Campo Veículo não informado.";
       $this->erro_campo = "ve13_veiculo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ve13_motorista == null ){ 
       $this->erro_sql = " Campo Motorista não informado.";
       $this->erro_campo = "ve13_motorista";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ve13_datainicial == null ){ 
       $this->erro_sql = " Campo Data Inicial não informado.";
       $this->erro_campo = "ve13_datainicial_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ve13_datafinal == null ){ 
       $this->erro_sql = " Campo Data Final não informado.";
       $this->erro_campo = "ve13_datafinal_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ve13_dataemissao == null ){ 
       $this->erro_sql = " Campo Data de Emissão não informado.";
       $this->erro_campo = "ve13_dataemissao_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ve13_departamento == null ){ 
       $this->erro_sql = " Campo Departamento não informado.";
       $this->erro_campo = "ve13_departamento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ve13_sequencial == "" || $ve13_sequencial == null ){
       $result = db_query("select nextval('autorizacaocirculacaoveiculo_ve13_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: autorizacaocirculacaoveiculo_ve13_sequencial_seq do campo: ve13_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ve13_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from autorizacaocirculacaoveiculo_ve13_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $ve13_sequencial)){
         $this->erro_sql = " Campo ve13_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ve13_sequencial = $ve13_sequencial; 
       }
     }
     if(($this->ve13_sequencial == null) || ($this->ve13_sequencial == "") ){ 
       $this->erro_sql = " Campo ve13_sequencial não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into autorizacaocirculacaoveiculo(
                                       ve13_sequencial 
                                      ,ve13_instituicao 
                                      ,ve13_veiculo 
                                      ,ve13_motorista 
                                      ,ve13_datainicial 
                                      ,ve13_datafinal 
                                      ,ve13_dataemissao 
                                      ,ve13_observacao 
                                      ,ve13_departamento 
                       )
                values (
                                $this->ve13_sequencial 
                               ,$this->ve13_instituicao 
                               ,$this->ve13_veiculo 
                               ,$this->ve13_motorista 
                               ,".($this->ve13_datainicial == "null" || $this->ve13_datainicial == ""?"null":"'".$this->ve13_datainicial."'")." 
                               ,".($this->ve13_datafinal == "null" || $this->ve13_datafinal == ""?"null":"'".$this->ve13_datafinal."'")." 
                               ,".($this->ve13_dataemissao == "null" || $this->ve13_dataemissao == ""?"null":"'".$this->ve13_dataemissao."'")." 
                               ,'$this->ve13_observacao' 
                               ,$this->ve13_departamento 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Autorização Circulação de Veículo ($this->ve13_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Autorização Circulação de Veículo já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Autorização Circulação de Veículo ($this->ve13_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ve13_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ve13_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,21365,'$this->ve13_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3845,21365,'','".AddSlashes(pg_result($resaco,0,'ve13_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3845,21366,'','".AddSlashes(pg_result($resaco,0,'ve13_instituicao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3845,21367,'','".AddSlashes(pg_result($resaco,0,'ve13_veiculo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3845,21368,'','".AddSlashes(pg_result($resaco,0,'ve13_motorista'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3845,21369,'','".AddSlashes(pg_result($resaco,0,'ve13_datainicial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3845,21370,'','".AddSlashes(pg_result($resaco,0,'ve13_datafinal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3845,21371,'','".AddSlashes(pg_result($resaco,0,'ve13_dataemissao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3845,21372,'','".AddSlashes(pg_result($resaco,0,'ve13_observacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3845,21377,'','".AddSlashes(pg_result($resaco,0,'ve13_departamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   public function alterar ($ve13_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update autorizacaocirculacaoveiculo set ";
     $virgula = "";
     if(trim($this->ve13_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ve13_sequencial"])){ 
       $sql  .= $virgula." ve13_sequencial = $this->ve13_sequencial ";
       $virgula = ",";
       if(trim($this->ve13_sequencial) == null ){ 
         $this->erro_sql = " Campo Código não informado.";
         $this->erro_campo = "ve13_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ve13_instituicao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ve13_instituicao"])){ 
       $sql  .= $virgula." ve13_instituicao = $this->ve13_instituicao ";
       $virgula = ",";
       if(trim($this->ve13_instituicao) == null ){ 
         $this->erro_sql = " Campo Instituição não informado.";
         $this->erro_campo = "ve13_instituicao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ve13_veiculo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ve13_veiculo"])){ 
       $sql  .= $virgula." ve13_veiculo = $this->ve13_veiculo ";
       $virgula = ",";
       if(trim($this->ve13_veiculo) == null ){ 
         $this->erro_sql = " Campo Veículo não informado.";
         $this->erro_campo = "ve13_veiculo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ve13_motorista)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ve13_motorista"])){ 
       $sql  .= $virgula." ve13_motorista = $this->ve13_motorista ";
       $virgula = ",";
       if(trim($this->ve13_motorista) == null ){ 
         $this->erro_sql = " Campo Motorista não informado.";
         $this->erro_campo = "ve13_motorista";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ve13_datainicial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ve13_datainicial_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ve13_datainicial_dia"] !="") ){ 
       $sql  .= $virgula." ve13_datainicial = '$this->ve13_datainicial' ";
       $virgula = ",";
       if(trim($this->ve13_datainicial) == null ){ 
         $this->erro_sql = " Campo Data Inicial não informado.";
         $this->erro_campo = "ve13_datainicial_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["ve13_datainicial_dia"])){ 
         $sql  .= $virgula." ve13_datainicial = null ";
         $virgula = ",";
         if(trim($this->ve13_datainicial) == null ){ 
           $this->erro_sql = " Campo Data Inicial não informado.";
           $this->erro_campo = "ve13_datainicial_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->ve13_datafinal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ve13_datafinal_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ve13_datafinal_dia"] !="") ){ 
       $sql  .= $virgula." ve13_datafinal = '$this->ve13_datafinal' ";
       $virgula = ",";
       if(trim($this->ve13_datafinal) == null ){ 
         $this->erro_sql = " Campo Data Final não informado.";
         $this->erro_campo = "ve13_datafinal_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["ve13_datafinal_dia"])){ 
         $sql  .= $virgula." ve13_datafinal = null ";
         $virgula = ",";
         if(trim($this->ve13_datafinal) == null ){ 
           $this->erro_sql = " Campo Data Final não informado.";
           $this->erro_campo = "ve13_datafinal_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->ve13_dataemissao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ve13_dataemissao_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ve13_dataemissao_dia"] !="") ){ 
       $sql  .= $virgula." ve13_dataemissao = '$this->ve13_dataemissao' ";
       $virgula = ",";
       if(trim($this->ve13_dataemissao) == null ){ 
         $this->erro_sql = " Campo Data de Emissão não informado.";
         $this->erro_campo = "ve13_dataemissao_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["ve13_dataemissao_dia"])){ 
         $sql  .= $virgula." ve13_dataemissao = null ";
         $virgula = ",";
         if(trim($this->ve13_dataemissao) == null ){ 
           $this->erro_sql = " Campo Data de Emissão não informado.";
           $this->erro_campo = "ve13_dataemissao_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->ve13_observacao) !== null || isset($GLOBALS["HTTP_POST_VARS"]["ve13_observacao"])){
       $sql  .= $virgula." ve13_observacao = '$this->ve13_observacao' ";
       $virgula = ",";
     }
     if(trim($this->ve13_departamento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ve13_departamento"])){
       $sql  .= $virgula." ve13_departamento = $this->ve13_departamento ";
       $virgula = ",";
       if(trim($this->ve13_departamento) == null ){ 
         $this->erro_sql = " Campo Departamento não informado.";
         $this->erro_campo = "ve13_departamento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ve13_sequencial!=null){
       $sql .= " ve13_sequencial = $this->ve13_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ve13_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,21365,'$this->ve13_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ve13_sequencial"]) || $this->ve13_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3845,21365,'".AddSlashes(pg_result($resaco,$conresaco,'ve13_sequencial'))."','$this->ve13_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ve13_instituicao"]) || $this->ve13_instituicao != "")
             $resac = db_query("insert into db_acount values($acount,3845,21366,'".AddSlashes(pg_result($resaco,$conresaco,'ve13_instituicao'))."','$this->ve13_instituicao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ve13_veiculo"]) || $this->ve13_veiculo != "")
             $resac = db_query("insert into db_acount values($acount,3845,21367,'".AddSlashes(pg_result($resaco,$conresaco,'ve13_veiculo'))."','$this->ve13_veiculo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ve13_motorista"]) || $this->ve13_motorista != "")
             $resac = db_query("insert into db_acount values($acount,3845,21368,'".AddSlashes(pg_result($resaco,$conresaco,'ve13_motorista'))."','$this->ve13_motorista',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ve13_datainicial"]) || $this->ve13_datainicial != "")
             $resac = db_query("insert into db_acount values($acount,3845,21369,'".AddSlashes(pg_result($resaco,$conresaco,'ve13_datainicial'))."','$this->ve13_datainicial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ve13_datafinal"]) || $this->ve13_datafinal != "")
             $resac = db_query("insert into db_acount values($acount,3845,21370,'".AddSlashes(pg_result($resaco,$conresaco,'ve13_datafinal'))."','$this->ve13_datafinal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ve13_dataemissao"]) || $this->ve13_dataemissao != "")
             $resac = db_query("insert into db_acount values($acount,3845,21371,'".AddSlashes(pg_result($resaco,$conresaco,'ve13_dataemissao'))."','$this->ve13_dataemissao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ve13_observacao"]) || $this->ve13_observacao !== null)
             $resac = db_query("insert into db_acount values($acount,3845,21372,'".AddSlashes(pg_result($resaco,$conresaco,'ve13_observacao'))."','$this->ve13_observacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ve13_departamento"]) || $this->ve13_departamento != "")
             $resac = db_query("insert into db_acount values($acount,3845,21377,'".AddSlashes(pg_result($resaco,$conresaco,'ve13_departamento'))."','$this->ve13_departamento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Autorização Circulação de Veículo não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ve13_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Autorização Circulação de Veículo não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ve13_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ve13_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   public function excluir ($ve13_sequencial=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($ve13_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,21365,'$ve13_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3845,21365,'','".AddSlashes(pg_result($resaco,$iresaco,'ve13_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3845,21366,'','".AddSlashes(pg_result($resaco,$iresaco,'ve13_instituicao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3845,21367,'','".AddSlashes(pg_result($resaco,$iresaco,'ve13_veiculo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3845,21368,'','".AddSlashes(pg_result($resaco,$iresaco,'ve13_motorista'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3845,21369,'','".AddSlashes(pg_result($resaco,$iresaco,'ve13_datainicial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3845,21370,'','".AddSlashes(pg_result($resaco,$iresaco,'ve13_datafinal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3845,21371,'','".AddSlashes(pg_result($resaco,$iresaco,'ve13_dataemissao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3845,21372,'','".AddSlashes(pg_result($resaco,$iresaco,'ve13_observacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3845,21377,'','".AddSlashes(pg_result($resaco,$iresaco,'ve13_departamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from autorizacaocirculacaoveiculo
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($ve13_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " ve13_sequencial = $ve13_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Autorização Circulação de Veículo não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ve13_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Autorização Circulação de Veículo não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ve13_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ve13_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao do recordset 
   public function sql_record($sql) { 
     $result = db_query($sql);
     if (!$result) {
       $this->numrows    = 0;
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Erro ao selecionar os registros.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $this->numrows = pg_num_rows($result);
      if ($this->numrows == 0) {
        $this->erro_banco = "";
        $this->erro_sql   = "Record Vazio na Tabela:autorizacaocirculacaoveiculo";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   public function sql_query ($ve13_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from autorizacaocirculacaoveiculo ";
     $sql .= "      inner join db_config  on  db_config.codigo = autorizacaocirculacaoveiculo.ve13_instituicao";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = autorizacaocirculacaoveiculo.ve13_departamento";
     $sql .= "      inner join veiculos  on  veiculos.ve01_codigo = autorizacaocirculacaoveiculo.ve13_veiculo";
     $sql .= "      inner join veicmotoristas  on  veicmotoristas.ve05_codigo = autorizacaocirculacaoveiculo.ve13_motorista";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = db_config.numcgm";
     $sql .= "      inner join db_tipoinstit  on  db_tipoinstit.db21_codtipo = db_config.db21_tipoinstit";
     $sql .= "      inner join ceplocalidades  on  ceplocalidades.cp05_codlocalidades = veiculos.ve01_ceplocalidades";
     $sql .= "      inner join veiccadtipo  on  veiccadtipo.ve20_codigo = veiculos.ve01_veiccadtipo";
     $sql .= "      inner join veiccadmarca  on  veiccadmarca.ve21_codigo = veiculos.ve01_veiccadmarca";
     $sql .= "      inner join veiccadmodelo  on  veiccadmodelo.ve22_codigo = veiculos.ve01_veiccadmodelo";
     $sql .= "      inner join veiccadcor  on  veiccadcor.ve23_codigo = veiculos.ve01_veiccadcor";
     $sql .= "      inner join veiccadtipocapacidade  on  veiccadtipocapacidade.ve24_codigo = veiculos.ve01_veiccadtipocapacidade";
     $sql .= "      inner join veiccadcategcnh  on  veiccadcategcnh.ve30_codigo = veiculos.ve01_veiccadcategcnh";
     $sql .= "      inner join veiccadproced  on  veiccadproced.ve25_codigo = veiculos.ve01_veiccadproced";
     $sql .= "      inner join veiccadpotencia  on  veiccadpotencia.ve31_codigo = veiculos.ve01_veiccadpotencia";
     $sql .= "      inner join veiccadcateg  as a on   a.ve32_codigo = veiculos.ve01_veiccadcateg";
     $sql .= "      inner join veictipoabast  on  veictipoabast.ve07_sequencial = veiculos.ve01_veictipoabast";
     $sql .= "      inner join cgm  as b on   b.z01_numcgm = veicmotoristas.ve05_numcgm";
     $sql .= "      inner join veiccadcategcnh  as c on   c.ve30_codigo = veicmotoristas.ve05_veiccadcategcnh";
     $sql .= "      inner join veiccadmotoristasit  on  veiccadmotoristasit.ve33_codigo = veicmotoristas.ve05_veiccadmotoristasit";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($ve13_sequencial)) {
         $sql2 .= " where autorizacaocirculacaoveiculo.ve13_sequencial = $ve13_sequencial "; 
       } 
     } else if (!empty($dbwhere)) {
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if (!empty($ordem)) {
       $sql .= " order by {$ordem}";
     }
     return $sql;
  }
   // funcao do sql 
   public function sql_query_file ($ve13_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from autorizacaocirculacaoveiculo ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($ve13_sequencial)){
         $sql2 .= " where autorizacaocirculacaoveiculo.ve13_sequencial = $ve13_sequencial "; 
       } 
     } else if (!empty($dbwhere)) {
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if (!empty($ordem)) {
       $sql .= " order by {$ordem}";
     }
     return $sql;
  }

}
