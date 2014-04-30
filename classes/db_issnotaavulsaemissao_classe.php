<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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

//MODULO: issqn
//CLASSE DA ENTIDADE issnotaavulsaemissao
class cl_issnotaavulsaemissao { 
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
   var $q69_sequencial = 0; 
   var $q69_issnotaavulsa = 0; 
   var $q69_usuario = 0; 
   var $q69_data_dia = null; 
   var $q69_data_mes = null; 
   var $q69_data_ano = null; 
   var $q69_data = null; 
   var $q69_hora = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 q69_sequencial = int4 = Código Sequencial 
                 q69_issnotaavulsa = int4 = Código da Nota 
                 q69_usuario = int4 = Usuário 
                 q69_data = date = Data da Emissão 
                 q69_hora = char(5) = Hora da Emissão 
                 ";
   //funcao construtor da classe 
   function cl_issnotaavulsaemissao() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("issnotaavulsaemissao"); 
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
       $this->q69_sequencial = ($this->q69_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["q69_sequencial"]:$this->q69_sequencial);
       $this->q69_issnotaavulsa = ($this->q69_issnotaavulsa == ""?@$GLOBALS["HTTP_POST_VARS"]["q69_issnotaavulsa"]:$this->q69_issnotaavulsa);
       $this->q69_usuario = ($this->q69_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["q69_usuario"]:$this->q69_usuario);
       if($this->q69_data == ""){
         $this->q69_data_dia = ($this->q69_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["q69_data_dia"]:$this->q69_data_dia);
         $this->q69_data_mes = ($this->q69_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["q69_data_mes"]:$this->q69_data_mes);
         $this->q69_data_ano = ($this->q69_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["q69_data_ano"]:$this->q69_data_ano);
         if($this->q69_data_dia != ""){
            $this->q69_data = $this->q69_data_ano."-".$this->q69_data_mes."-".$this->q69_data_dia;
         }
       }
       $this->q69_hora = ($this->q69_hora == ""?@$GLOBALS["HTTP_POST_VARS"]["q69_hora"]:$this->q69_hora);
     }else{
       $this->q69_sequencial = ($this->q69_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["q69_sequencial"]:$this->q69_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($q69_sequencial){ 
      $this->atualizacampos();
     if($this->q69_issnotaavulsa == null ){ 
       $this->erro_sql = " Campo Código da Nota nao Informado.";
       $this->erro_campo = "q69_issnotaavulsa";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q69_usuario == null ){ 
       $this->erro_sql = " Campo Usuário nao Informado.";
       $this->erro_campo = "q69_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q69_data == null ){ 
       $this->erro_sql = " Campo Data da Emissão nao Informado.";
       $this->erro_campo = "q69_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q69_hora == null ){ 
       $this->erro_sql = " Campo Hora da Emissão nao Informado.";
       $this->erro_campo = "q69_hora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($q69_sequencial == "" || $q69_sequencial == null ){
       $result = db_query("select nextval('issnotaavulsaemissao_q69_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: issnotaavulsaemissao_q69_sequencial_seq do campo: q69_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->q69_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from issnotaavulsaemissao_q69_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $q69_sequencial)){
         $this->erro_sql = " Campo q69_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->q69_sequencial = $q69_sequencial; 
       }
     }
     if(($this->q69_sequencial == null) || ($this->q69_sequencial == "") ){ 
       $this->erro_sql = " Campo q69_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into issnotaavulsaemissao(
                                       q69_sequencial 
                                      ,q69_issnotaavulsa 
                                      ,q69_usuario 
                                      ,q69_data 
                                      ,q69_hora 
                       )
                values (
                                $this->q69_sequencial 
                               ,$this->q69_issnotaavulsa 
                               ,$this->q69_usuario 
                               ,".($this->q69_data == "null" || $this->q69_data == ""?"null":"'".$this->q69_data."'")." 
                               ,'$this->q69_hora' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Emissao de Nota Avulsa ($this->q69_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Emissao de Nota Avulsa já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Emissao de Nota Avulsa ($this->q69_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->q69_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->q69_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,10755,'$this->q69_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,1851,10755,'','".AddSlashes(pg_result($resaco,0,'q69_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1851,10756,'','".AddSlashes(pg_result($resaco,0,'q69_issnotaavulsa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1851,10757,'','".AddSlashes(pg_result($resaco,0,'q69_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1851,10758,'','".AddSlashes(pg_result($resaco,0,'q69_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1851,10759,'','".AddSlashes(pg_result($resaco,0,'q69_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($q69_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update issnotaavulsaemissao set ";
     $virgula = "";
     if(trim($this->q69_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q69_sequencial"])){ 
       $sql  .= $virgula." q69_sequencial = $this->q69_sequencial ";
       $virgula = ",";
       if(trim($this->q69_sequencial) == null ){ 
         $this->erro_sql = " Campo Código Sequencial nao Informado.";
         $this->erro_campo = "q69_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q69_issnotaavulsa)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q69_issnotaavulsa"])){ 
       $sql  .= $virgula." q69_issnotaavulsa = $this->q69_issnotaavulsa ";
       $virgula = ",";
       if(trim($this->q69_issnotaavulsa) == null ){ 
         $this->erro_sql = " Campo Código da Nota nao Informado.";
         $this->erro_campo = "q69_issnotaavulsa";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q69_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q69_usuario"])){ 
       $sql  .= $virgula." q69_usuario = $this->q69_usuario ";
       $virgula = ",";
       if(trim($this->q69_usuario) == null ){ 
         $this->erro_sql = " Campo Usuário nao Informado.";
         $this->erro_campo = "q69_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q69_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q69_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["q69_data_dia"] !="") ){ 
       $sql  .= $virgula." q69_data = '$this->q69_data' ";
       $virgula = ",";
       if(trim($this->q69_data) == null ){ 
         $this->erro_sql = " Campo Data da Emissão nao Informado.";
         $this->erro_campo = "q69_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["q69_data_dia"])){ 
         $sql  .= $virgula." q69_data = null ";
         $virgula = ",";
         if(trim($this->q69_data) == null ){ 
           $this->erro_sql = " Campo Data da Emissão nao Informado.";
           $this->erro_campo = "q69_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->q69_hora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q69_hora"])){ 
       $sql  .= $virgula." q69_hora = '$this->q69_hora' ";
       $virgula = ",";
       if(trim($this->q69_hora) == null ){ 
         $this->erro_sql = " Campo Hora da Emissão nao Informado.";
         $this->erro_campo = "q69_hora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($q69_sequencial!=null){
       $sql .= " q69_sequencial = $this->q69_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->q69_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,10755,'$this->q69_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q69_sequencial"]))
           $resac = db_query("insert into db_acount values($acount,1851,10755,'".AddSlashes(pg_result($resaco,$conresaco,'q69_sequencial'))."','$this->q69_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q69_issnotaavulsa"]))
           $resac = db_query("insert into db_acount values($acount,1851,10756,'".AddSlashes(pg_result($resaco,$conresaco,'q69_issnotaavulsa'))."','$this->q69_issnotaavulsa',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q69_usuario"]))
           $resac = db_query("insert into db_acount values($acount,1851,10757,'".AddSlashes(pg_result($resaco,$conresaco,'q69_usuario'))."','$this->q69_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q69_data"]))
           $resac = db_query("insert into db_acount values($acount,1851,10758,'".AddSlashes(pg_result($resaco,$conresaco,'q69_data'))."','$this->q69_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q69_hora"]))
           $resac = db_query("insert into db_acount values($acount,1851,10759,'".AddSlashes(pg_result($resaco,$conresaco,'q69_hora'))."','$this->q69_hora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Emissao de Nota Avulsa nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->q69_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Emissao de Nota Avulsa nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->q69_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->q69_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($q69_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($q69_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,10755,'$q69_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,1851,10755,'','".AddSlashes(pg_result($resaco,$iresaco,'q69_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1851,10756,'','".AddSlashes(pg_result($resaco,$iresaco,'q69_issnotaavulsa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1851,10757,'','".AddSlashes(pg_result($resaco,$iresaco,'q69_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1851,10758,'','".AddSlashes(pg_result($resaco,$iresaco,'q69_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1851,10759,'','".AddSlashes(pg_result($resaco,$iresaco,'q69_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from issnotaavulsaemissao
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($q69_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " q69_sequencial = $q69_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Emissao de Nota Avulsa nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$q69_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Emissao de Nota Avulsa nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$q69_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$q69_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao do recordset 
   function sql_record($sql) { 
     $result = db_query($sql);
     if($result==false){
       $this->numrows    = 0;
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Erro ao selecionar os registros.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $this->numrows = pg_numrows($result);
      if($this->numrows==0){
        $this->erro_banco = "";
        $this->erro_sql   = "Record Vazio na Tabela:issnotaavulsaemissao";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $q69_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
     $sql = "select ";
     if($campos != "*" ){
       $campos_sql = split("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }else{
       $sql .= $campos;
     }
     $sql .= " from issnotaavulsaemissao ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = issnotaavulsaemissao.q69_usuario";
     $sql .= "      inner join issnotaavulsa  on  issnotaavulsa.q51_sequencial = issnotaavulsaemissao.q69_issnotaavulsa";
     $sql .= "      inner join issbase  on  issbase.q02_inscr = issnotaavulsa.q51_inscr";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = issnotaavulsa.q51_usuario";
     $sql2 = "";
     if($dbwhere==""){
       if($q69_sequencial!=null ){
         $sql2 .= " where issnotaavulsaemissao.q69_sequencial = $q69_sequencial "; 
       } 
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if($ordem != null ){
       $sql .= " order by ";
       $campos_sql = split("#",$ordem);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }
     return $sql;
  }
   function sql_query_file ( $q69_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
     $sql = "select ";
     if($campos != "*" ){
       $campos_sql = split("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }else{
       $sql .= $campos;
     }
     $sql .= " from issnotaavulsaemissao ";
     $sql2 = "";
     if($dbwhere==""){
       if($q69_sequencial!=null ){
         $sql2 .= " where issnotaavulsaemissao.q69_sequencial = $q69_sequencial "; 
       } 
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if($ordem != null ){
       $sql .= " order by ";
       $campos_sql = split("#",$ordem);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }
     return $sql;
  }
}
?>