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

//MODULO: ouvidoria
//CLASSE DA ENTIDADE ouvidoriaatendimentoretornotelefone
class cl_ouvidoriaatendimentoretornotelefone { 
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
   var $ov14_sequencial = 0; 
   var $ov14_ouvidoriaatendimento = 0; 
   var $ov14_numero = null; 
   var $ov14_tipotelefone = 0; 
   var $ov14_ddd = null; 
   var $ov14_ramal = null; 
   var $ov14_obs = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ov14_sequencial = int4 = Sequencial 
                 ov14_ouvidoriaatendimento = int4 = Atendimento 
                 ov14_numero = varchar(10) = Número 
                 ov14_tipotelefone = int4 = Tipo Telefone 
                 ov14_ddd = varchar(10) = DDD 
                 ov14_ramal = varchar(10) = Ramal 
                 ov14_obs = text = Observação 
                 ";
   //funcao construtor da classe 
   function cl_ouvidoriaatendimentoretornotelefone() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("ouvidoriaatendimentoretornotelefone"); 
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
       $this->ov14_sequencial = ($this->ov14_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ov14_sequencial"]:$this->ov14_sequencial);
       $this->ov14_ouvidoriaatendimento = ($this->ov14_ouvidoriaatendimento == ""?@$GLOBALS["HTTP_POST_VARS"]["ov14_ouvidoriaatendimento"]:$this->ov14_ouvidoriaatendimento);
       $this->ov14_numero = ($this->ov14_numero == ""?@$GLOBALS["HTTP_POST_VARS"]["ov14_numero"]:$this->ov14_numero);
       $this->ov14_tipotelefone = ($this->ov14_tipotelefone == ""?@$GLOBALS["HTTP_POST_VARS"]["ov14_tipotelefone"]:$this->ov14_tipotelefone);
       $this->ov14_ddd = ($this->ov14_ddd == ""?@$GLOBALS["HTTP_POST_VARS"]["ov14_ddd"]:$this->ov14_ddd);
       $this->ov14_ramal = ($this->ov14_ramal == ""?@$GLOBALS["HTTP_POST_VARS"]["ov14_ramal"]:$this->ov14_ramal);
       $this->ov14_obs = ($this->ov14_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["ov14_obs"]:$this->ov14_obs);
     }else{
       $this->ov14_sequencial = ($this->ov14_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ov14_sequencial"]:$this->ov14_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($ov14_sequencial){ 
      $this->atualizacampos();
     if($this->ov14_ouvidoriaatendimento == null ){ 
       $this->erro_sql = " Campo Atendimento nao Informado.";
       $this->erro_campo = "ov14_ouvidoriaatendimento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ov14_numero == null ){ 
       $this->erro_sql = " Campo Número nao Informado.";
       $this->erro_campo = "ov14_numero";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ov14_tipotelefone == null ){ 
       $this->erro_sql = " Campo Tipo Telefone nao Informado.";
       $this->erro_campo = "ov14_tipotelefone";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ov14_ddd == null ){ 
       $this->ov14_ddd = "0";
     }
     if($this->ov14_ramal == null ){ 
       $this->ov14_ramal = "0";
     }
     if($ov14_sequencial == "" || $ov14_sequencial == null ){
       $result = db_query("select nextval('ouvidoriaatendimentoretornotelefone_ov14_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: ouvidoriaatendimentoretornotelefone_ov14_sequencial_seq do campo: ov14_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ov14_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from ouvidoriaatendimentoretornotelefone_ov14_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $ov14_sequencial)){
         $this->erro_sql = " Campo ov14_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ov14_sequencial = $ov14_sequencial; 
       }
     }
     if(($this->ov14_sequencial == null) || ($this->ov14_sequencial == "") ){ 
       $this->erro_sql = " Campo ov14_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into ouvidoriaatendimentoretornotelefone(
                                       ov14_sequencial 
                                      ,ov14_ouvidoriaatendimento 
                                      ,ov14_numero 
                                      ,ov14_tipotelefone 
                                      ,ov14_ddd 
                                      ,ov14_ramal 
                                      ,ov14_obs 
                       )
                values (
                                $this->ov14_sequencial 
                               ,$this->ov14_ouvidoriaatendimento 
                               ,'$this->ov14_numero' 
                               ,$this->ov14_tipotelefone 
                               ,'$this->ov14_ddd' 
                               ,'$this->ov14_ramal' 
                               ,'$this->ov14_obs' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Telefones de Retorno do Atendimento da Ouvidoria ($this->ov14_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Telefones de Retorno do Atendimento da Ouvidoria já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Telefones de Retorno do Atendimento da Ouvidoria ($this->ov14_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ov14_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ov14_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,14802,'$this->ov14_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2605,14802,'','".AddSlashes(pg_result($resaco,0,'ov14_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2605,14803,'','".AddSlashes(pg_result($resaco,0,'ov14_ouvidoriaatendimento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2605,14804,'','".AddSlashes(pg_result($resaco,0,'ov14_numero'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2605,14805,'','".AddSlashes(pg_result($resaco,0,'ov14_tipotelefone'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2605,14806,'','".AddSlashes(pg_result($resaco,0,'ov14_ddd'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2605,14807,'','".AddSlashes(pg_result($resaco,0,'ov14_ramal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2605,14808,'','".AddSlashes(pg_result($resaco,0,'ov14_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ov14_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update ouvidoriaatendimentoretornotelefone set ";
     $virgula = "";
     if(trim($this->ov14_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ov14_sequencial"])){ 
       $sql  .= $virgula." ov14_sequencial = $this->ov14_sequencial ";
       $virgula = ",";
       if(trim($this->ov14_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "ov14_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ov14_ouvidoriaatendimento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ov14_ouvidoriaatendimento"])){ 
       $sql  .= $virgula." ov14_ouvidoriaatendimento = $this->ov14_ouvidoriaatendimento ";
       $virgula = ",";
       if(trim($this->ov14_ouvidoriaatendimento) == null ){ 
         $this->erro_sql = " Campo Atendimento nao Informado.";
         $this->erro_campo = "ov14_ouvidoriaatendimento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ov14_numero)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ov14_numero"])){ 
       $sql  .= $virgula." ov14_numero = '$this->ov14_numero' ";
       $virgula = ",";
       if(trim($this->ov14_numero) == null ){ 
         $this->erro_sql = " Campo Número nao Informado.";
         $this->erro_campo = "ov14_numero";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ov14_tipotelefone)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ov14_tipotelefone"])){ 
       $sql  .= $virgula." ov14_tipotelefone = $this->ov14_tipotelefone ";
       $virgula = ",";
       if(trim($this->ov14_tipotelefone) == null ){ 
         $this->erro_sql = " Campo Tipo Telefone nao Informado.";
         $this->erro_campo = "ov14_tipotelefone";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ov14_ddd)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ov14_ddd"])){ 
       $sql  .= $virgula." ov14_ddd = '$this->ov14_ddd' ";
       $virgula = ",";
     }
     if(trim($this->ov14_ramal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ov14_ramal"])){ 
       $sql  .= $virgula." ov14_ramal = '$this->ov14_ramal' ";
       $virgula = ",";
     }
     if(trim($this->ov14_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ov14_obs"])){ 
       $sql  .= $virgula." ov14_obs = '$this->ov14_obs' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($ov14_sequencial!=null){
       $sql .= " ov14_sequencial = $this->ov14_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ov14_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,14802,'$this->ov14_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ov14_sequencial"]) || $this->ov14_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,2605,14802,'".AddSlashes(pg_result($resaco,$conresaco,'ov14_sequencial'))."','$this->ov14_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ov14_ouvidoriaatendimento"]) || $this->ov14_ouvidoriaatendimento != "")
           $resac = db_query("insert into db_acount values($acount,2605,14803,'".AddSlashes(pg_result($resaco,$conresaco,'ov14_ouvidoriaatendimento'))."','$this->ov14_ouvidoriaatendimento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ov14_numero"]) || $this->ov14_numero != "")
           $resac = db_query("insert into db_acount values($acount,2605,14804,'".AddSlashes(pg_result($resaco,$conresaco,'ov14_numero'))."','$this->ov14_numero',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ov14_tipotelefone"]) || $this->ov14_tipotelefone != "")
           $resac = db_query("insert into db_acount values($acount,2605,14805,'".AddSlashes(pg_result($resaco,$conresaco,'ov14_tipotelefone'))."','$this->ov14_tipotelefone',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ov14_ddd"]) || $this->ov14_ddd != "")
           $resac = db_query("insert into db_acount values($acount,2605,14806,'".AddSlashes(pg_result($resaco,$conresaco,'ov14_ddd'))."','$this->ov14_ddd',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ov14_ramal"]) || $this->ov14_ramal != "")
           $resac = db_query("insert into db_acount values($acount,2605,14807,'".AddSlashes(pg_result($resaco,$conresaco,'ov14_ramal'))."','$this->ov14_ramal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ov14_obs"]) || $this->ov14_obs != "")
           $resac = db_query("insert into db_acount values($acount,2605,14808,'".AddSlashes(pg_result($resaco,$conresaco,'ov14_obs'))."','$this->ov14_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Telefones de Retorno do Atendimento da Ouvidoria nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ov14_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Telefones de Retorno do Atendimento da Ouvidoria nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ov14_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ov14_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ov14_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ov14_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,14802,'$ov14_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2605,14802,'','".AddSlashes(pg_result($resaco,$iresaco,'ov14_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2605,14803,'','".AddSlashes(pg_result($resaco,$iresaco,'ov14_ouvidoriaatendimento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2605,14804,'','".AddSlashes(pg_result($resaco,$iresaco,'ov14_numero'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2605,14805,'','".AddSlashes(pg_result($resaco,$iresaco,'ov14_tipotelefone'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2605,14806,'','".AddSlashes(pg_result($resaco,$iresaco,'ov14_ddd'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2605,14807,'','".AddSlashes(pg_result($resaco,$iresaco,'ov14_ramal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2605,14808,'','".AddSlashes(pg_result($resaco,$iresaco,'ov14_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from ouvidoriaatendimentoretornotelefone
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ov14_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ov14_sequencial = $ov14_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Telefones de Retorno do Atendimento da Ouvidoria nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ov14_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Telefones de Retorno do Atendimento da Ouvidoria nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ov14_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ov14_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:ouvidoriaatendimentoretornotelefone";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $ov14_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from ouvidoriaatendimentoretornotelefone ";
     $sql .= "      inner join ouvidoriaatendimento  on  ouvidoriaatendimento.ov01_sequencial = ouvidoriaatendimentoretornotelefone.ov14_ouvidoriaatendimento";
     $sql .= "      inner join telefonetipo  on  telefonetipo.ov23_sequencial = ouvidoriaatendimentoretornotelefone.ov14_tipotelefone";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = ouvidoriaatendimento.ov01_usuario";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = ouvidoriaatendimento.ov01_depart";
     $sql .= "      inner join tipoproc  on  tipoproc.p51_codigo = ouvidoriaatendimento.ov01_tipoprocesso";
     $sql .= "      inner join tipoidentificacao  on  tipoidentificacao.ov05_sequencial = ouvidoriaatendimento.ov01_tipoidentificacao";
     $sql .= "      inner join formareclamacao  on  formareclamacao.p42_sequencial = ouvidoriaatendimento.ov01_formareclamacao";
     $sql .= "      inner join situacaoouvidoriaatendimento  on  situacaoouvidoriaatendimento.ov18_sequencial = ouvidoriaatendimento.ov01_situacaoouvidoriaatendimento";
     $sql2 = "";
     if($dbwhere==""){
       if($ov14_sequencial!=null ){
         $sql2 .= " where ouvidoriaatendimentoretornotelefone.ov14_sequencial = $ov14_sequencial "; 
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
   function sql_query_file ( $ov14_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from ouvidoriaatendimentoretornotelefone ";
     $sql2 = "";
     if($dbwhere==""){
       if($ov14_sequencial!=null ){
         $sql2 .= " where ouvidoriaatendimentoretornotelefone.ov14_sequencial = $ov14_sequencial "; 
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