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

//MODULO: patrim
//CLASSE DA ENTIDADE histbensocorrencia
class cl_histbensocorrencia { 
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
   var $t69_sequencial = 0; 
   var $t69_codbem = 0; 
   var $t69_ocorrenciasbens = 0; 
   var $t69_obs = null; 
   var $t69_dthist_dia = null; 
   var $t69_dthist_mes = null; 
   var $t69_dthist_ano = null; 
   var $t69_dthist = null; 
   var $t69_hora = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 t69_sequencial = int4 = Sequ�ncial 
                 t69_codbem = int4 = C�d Bem 
                 t69_ocorrenciasbens = int4 = C�d Ocorr�ncia 
                 t69_obs = varchar(50) = Observa��o 
                 t69_dthist = date = Data do Hist�rico 
                 t69_hora = char(5) = Hora da Ocorr�ncia 
                 ";
   //funcao construtor da classe 
   function cl_histbensocorrencia() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("histbensocorrencia"); 
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
       $this->t69_sequencial = ($this->t69_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["t69_sequencial"]:$this->t69_sequencial);
       $this->t69_codbem = ($this->t69_codbem == ""?@$GLOBALS["HTTP_POST_VARS"]["t69_codbem"]:$this->t69_codbem);
       $this->t69_ocorrenciasbens = ($this->t69_ocorrenciasbens == ""?@$GLOBALS["HTTP_POST_VARS"]["t69_ocorrenciasbens"]:$this->t69_ocorrenciasbens);
       $this->t69_obs = ($this->t69_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["t69_obs"]:$this->t69_obs);
       if($this->t69_dthist == ""){
         $this->t69_dthist_dia = ($this->t69_dthist_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["t69_dthist_dia"]:$this->t69_dthist_dia);
         $this->t69_dthist_mes = ($this->t69_dthist_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["t69_dthist_mes"]:$this->t69_dthist_mes);
         $this->t69_dthist_ano = ($this->t69_dthist_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["t69_dthist_ano"]:$this->t69_dthist_ano);
         if($this->t69_dthist_dia != ""){
            $this->t69_dthist = $this->t69_dthist_ano."-".$this->t69_dthist_mes."-".$this->t69_dthist_dia;
         }
       }
       $this->t69_hora = ($this->t69_hora == ""?@$GLOBALS["HTTP_POST_VARS"]["t69_hora"]:$this->t69_hora);
     }else{
       $this->t69_sequencial = ($this->t69_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["t69_sequencial"]:$this->t69_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($t69_sequencial){ 
      $this->atualizacampos();
     if($this->t69_codbem == null ){ 
       $this->erro_sql = " Campo C�d Bem nao Informado.";
       $this->erro_campo = "t69_codbem";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->t69_ocorrenciasbens == null ){ 
       $this->erro_sql = " Campo C�d Ocorr�ncia nao Informado.";
       $this->erro_campo = "t69_ocorrenciasbens";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->t69_obs == null ){ 
       $this->erro_sql = " Campo Observa��o nao Informado.";
       $this->erro_campo = "t69_obs";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->t69_dthist == null ){ 
       $this->erro_sql = " Campo Data do Hist�rico nao Informado.";
       $this->erro_campo = "t69_dthist_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->t69_hora == null ){ 
       $this->erro_sql = " Campo Hora da Ocorr�ncia nao Informado.";
       $this->erro_campo = "t69_hora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($t69_sequencial == "" || $t69_sequencial == null ){
       $result = db_query("select nextval('histbensocorrencia_t69_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: histbensocorrencia_t69_sequencial_seq do campo: t69_sequencial"; 
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->t69_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from histbensocorrencia_t69_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $t69_sequencial)){
         $this->erro_sql = " Campo t69_sequencial maior que �ltimo n�mero da sequencia.";
         $this->erro_banco = "Sequencia menor que este n�mero.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->t69_sequencial = $t69_sequencial; 
       }
     }
     if(($this->t69_sequencial == null) || ($this->t69_sequencial == "") ){ 
       $this->erro_sql = " Campo t69_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into histbensocorrencia(
                                       t69_sequencial 
                                      ,t69_codbem 
                                      ,t69_ocorrenciasbens 
                                      ,t69_obs 
                                      ,t69_dthist 
                                      ,t69_hora 
                       )
                values (
                                $this->t69_sequencial 
                               ,$this->t69_codbem 
                               ,$this->t69_ocorrenciasbens 
                               ,'$this->t69_obs' 
                               ,".($this->t69_dthist == "null" || $this->t69_dthist == ""?"null":"'".$this->t69_dthist."'")." 
                               ,'$this->t69_hora' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Hist�rico de Ocorr�ncias do Bem ($this->t69_sequencial) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Hist�rico de Ocorr�ncias do Bem j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Hist�rico de Ocorr�ncias do Bem ($this->t69_sequencial) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->t69_sequencial;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->t69_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,13856,'$this->t69_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2424,13856,'','".AddSlashes(pg_result($resaco,0,'t69_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2424,13857,'','".AddSlashes(pg_result($resaco,0,'t69_codbem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2424,13858,'','".AddSlashes(pg_result($resaco,0,'t69_ocorrenciasbens'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2424,13861,'','".AddSlashes(pg_result($resaco,0,'t69_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2424,13859,'','".AddSlashes(pg_result($resaco,0,'t69_dthist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2424,13860,'','".AddSlashes(pg_result($resaco,0,'t69_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($t69_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update histbensocorrencia set ";
     $virgula = "";
     if(trim($this->t69_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t69_sequencial"])){ 
       $sql  .= $virgula." t69_sequencial = $this->t69_sequencial ";
       $virgula = ",";
       if(trim($this->t69_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequ�ncial nao Informado.";
         $this->erro_campo = "t69_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->t69_codbem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t69_codbem"])){ 
       $sql  .= $virgula." t69_codbem = $this->t69_codbem ";
       $virgula = ",";
       if(trim($this->t69_codbem) == null ){ 
         $this->erro_sql = " Campo C�d Bem nao Informado.";
         $this->erro_campo = "t69_codbem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->t69_ocorrenciasbens)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t69_ocorrenciasbens"])){ 
       $sql  .= $virgula." t69_ocorrenciasbens = $this->t69_ocorrenciasbens ";
       $virgula = ",";
       if(trim($this->t69_ocorrenciasbens) == null ){ 
         $this->erro_sql = " Campo C�d Ocorr�ncia nao Informado.";
         $this->erro_campo = "t69_ocorrenciasbens";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->t69_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t69_obs"])){ 
       $sql  .= $virgula." t69_obs = '$this->t69_obs' ";
       $virgula = ",";
       if(trim($this->t69_obs) == null ){ 
         $this->erro_sql = " Campo Observa��o nao Informado.";
         $this->erro_campo = "t69_obs";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->t69_dthist)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t69_dthist_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["t69_dthist_dia"] !="") ){ 
       $sql  .= $virgula." t69_dthist = '$this->t69_dthist' ";
       $virgula = ",";
       if(trim($this->t69_dthist) == null ){ 
         $this->erro_sql = " Campo Data do Hist�rico nao Informado.";
         $this->erro_campo = "t69_dthist_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["t69_dthist_dia"])){ 
         $sql  .= $virgula." t69_dthist = null ";
         $virgula = ",";
         if(trim($this->t69_dthist) == null ){ 
           $this->erro_sql = " Campo Data do Hist�rico nao Informado.";
           $this->erro_campo = "t69_dthist_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->t69_hora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t69_hora"])){ 
       $sql  .= $virgula." t69_hora = '$this->t69_hora' ";
       $virgula = ",";
       if(trim($this->t69_hora) == null ){ 
         $this->erro_sql = " Campo Hora da Ocorr�ncia nao Informado.";
         $this->erro_campo = "t69_hora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($t69_sequencial!=null){
       $sql .= " t69_sequencial = $this->t69_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->t69_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,13856,'$this->t69_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t69_sequencial"]))
           $resac = db_query("insert into db_acount values($acount,2424,13856,'".AddSlashes(pg_result($resaco,$conresaco,'t69_sequencial'))."','$this->t69_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t69_codbem"]))
           $resac = db_query("insert into db_acount values($acount,2424,13857,'".AddSlashes(pg_result($resaco,$conresaco,'t69_codbem'))."','$this->t69_codbem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t69_ocorrenciasbens"]))
           $resac = db_query("insert into db_acount values($acount,2424,13858,'".AddSlashes(pg_result($resaco,$conresaco,'t69_ocorrenciasbens'))."','$this->t69_ocorrenciasbens',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t69_obs"]))
           $resac = db_query("insert into db_acount values($acount,2424,13861,'".AddSlashes(pg_result($resaco,$conresaco,'t69_obs'))."','$this->t69_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t69_dthist"]))
           $resac = db_query("insert into db_acount values($acount,2424,13859,'".AddSlashes(pg_result($resaco,$conresaco,'t69_dthist'))."','$this->t69_dthist',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t69_hora"]))
           $resac = db_query("insert into db_acount values($acount,2424,13860,'".AddSlashes(pg_result($resaco,$conresaco,'t69_hora'))."','$this->t69_hora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Hist�rico de Ocorr�ncias do Bem nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->t69_sequencial;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Hist�rico de Ocorr�ncias do Bem nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->t69_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->t69_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($t69_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($t69_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,13856,'$t69_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2424,13856,'','".AddSlashes(pg_result($resaco,$iresaco,'t69_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2424,13857,'','".AddSlashes(pg_result($resaco,$iresaco,'t69_codbem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2424,13858,'','".AddSlashes(pg_result($resaco,$iresaco,'t69_ocorrenciasbens'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2424,13861,'','".AddSlashes(pg_result($resaco,$iresaco,'t69_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2424,13859,'','".AddSlashes(pg_result($resaco,$iresaco,'t69_dthist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2424,13860,'','".AddSlashes(pg_result($resaco,$iresaco,'t69_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from histbensocorrencia
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($t69_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " t69_sequencial = $t69_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Hist�rico de Ocorr�ncias do Bem nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$t69_sequencial;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Hist�rico de Ocorr�ncias do Bem nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$t69_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$t69_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
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
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $this->numrows = pg_numrows($result);
      if($this->numrows==0){
        $this->erro_banco = "";
        $this->erro_sql   = "Record Vazio na Tabela:histbensocorrencia";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $t69_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from histbensocorrencia ";
     $sql .= "      inner join bens  on  bens.t52_bem = histbensocorrencia.t69_codbem";
     $sql .= "      inner join ocorrenciabens  on  ocorrenciabens.t68_sequencial = histbensocorrencia.t69_ocorrenciasbens";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = bens.t52_numcgm";
     $sql .= "      inner join db_config  on  db_config.codigo = bens.t52_instit";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = bens.t52_depart";
     $sql .= "      inner join clabens  on  clabens.t64_codcla = bens.t52_codcla";
     $sql .= "      inner join bensmarca  on  bensmarca.t65_sequencial = bens.t52_bensmarca";
     $sql .= "      inner join bensmodelo  on  bensmodelo.t66_sequencial = bens.t52_bensmodelo";
     $sql .= "      inner join bensmedida  on  bensmedida.t67_sequencial = bens.t52_bensmedidda";
     $sql2 = "";
     if($dbwhere==""){
       if($t69_sequencial!=null ){
         $sql2 .= " where histbensocorrencia.t69_sequencial = $t69_sequencial "; 
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
   // funcao do sql 
   function sql_query_file ( $t69_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from histbensocorrencia ";
     $sql2 = "";
     if($dbwhere==""){
       if($t69_sequencial!=null ){
         $sql2 .= " where histbensocorrencia.t69_sequencial = $t69_sequencial "; 
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