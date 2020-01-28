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

//MODULO: pessoal
//CLASSE DA ENTIDADE selecaopontorubricas
class cl_selecaopontorubricas { 
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
   var $r73_sequencial = 0; 
   var $r73_selecaoponto = 0; 
   var $r73_rubric = null; 
   var $r73_instit = 0; 
   var $r73_tipo = 0; 
   var $r73_valor = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 r73_sequencial = int4 = Sequencial 
                 r73_selecaoponto = int4 = Sele��o Ponto 
                 r73_rubric = char(4) = C�digo da Rubrica 
                 r73_instit = int4 = Codigo da Institui��o 
                 r73_tipo = int4 = Tipo de Valor 
                 r73_valor = float8 = Valor 
                 ";
   //funcao construtor da classe 
   function cl_selecaopontorubricas() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("selecaopontorubricas"); 
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
       $this->r73_sequencial = ($this->r73_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["r73_sequencial"]:$this->r73_sequencial);
       $this->r73_selecaoponto = ($this->r73_selecaoponto == ""?@$GLOBALS["HTTP_POST_VARS"]["r73_selecaoponto"]:$this->r73_selecaoponto);
       $this->r73_rubric = ($this->r73_rubric == ""?@$GLOBALS["HTTP_POST_VARS"]["r73_rubric"]:$this->r73_rubric);
       $this->r73_instit = ($this->r73_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["r73_instit"]:$this->r73_instit);
       $this->r73_tipo = ($this->r73_tipo == ""?@$GLOBALS["HTTP_POST_VARS"]["r73_tipo"]:$this->r73_tipo);
       $this->r73_valor = ($this->r73_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["r73_valor"]:$this->r73_valor);
     }else{
       $this->r73_sequencial = ($this->r73_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["r73_sequencial"]:$this->r73_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($r73_sequencial){ 
      $this->atualizacampos();
     if($this->r73_selecaoponto == null ){ 
       $this->erro_sql = " Campo Sele��o Ponto nao Informado.";
       $this->erro_campo = "r73_selecaoponto";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r73_rubric == null ){ 
       $this->erro_sql = " Campo C�digo da Rubrica nao Informado.";
       $this->erro_campo = "r73_rubric";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r73_instit == null ){ 
       $this->erro_sql = " Campo Codigo da Institui��o nao Informado.";
       $this->erro_campo = "r73_instit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r73_tipo == null ){ 
       $this->erro_sql = " Campo Tipo de Valor nao Informado.";
       $this->erro_campo = "r73_tipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r73_valor == null ){ 
       $this->r73_valor = "null";
     }
     if($r73_sequencial == "" || $r73_sequencial == null ){
       $result = db_query("select nextval('selecaopontorubricas_r73_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: selecaopontorubricas_r73_sequencial_seq do campo: r73_sequencial"; 
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->r73_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from selecaopontorubricas_r73_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $r73_sequencial)){
         $this->erro_sql = " Campo r73_sequencial maior que �ltimo n�mero da sequencia.";
         $this->erro_banco = "Sequencia menor que este n�mero.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->r73_sequencial = $r73_sequencial; 
       }
     }
     if(($this->r73_sequencial == null) || ($this->r73_sequencial == "") ){ 
       $this->erro_sql = " Campo r73_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into selecaopontorubricas(
                                       r73_sequencial 
                                      ,r73_selecaoponto 
                                      ,r73_rubric 
                                      ,r73_instit 
                                      ,r73_tipo 
                                      ,r73_valor 
                       )
                values (
                                $this->r73_sequencial 
                               ,$this->r73_selecaoponto 
                               ,'$this->r73_rubric' 
                               ,$this->r73_instit 
                               ,$this->r73_tipo 
                               ,$this->r73_valor 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Configura��o das Rubricas do Ponto por Sele��o ($this->r73_sequencial) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Configura��o das Rubricas do Ponto por Sele��o j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Configura��o das Rubricas do Ponto por Sele��o ($this->r73_sequencial) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->r73_sequencial;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->r73_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,14213,'$this->r73_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2500,14213,'','".AddSlashes(pg_result($resaco,0,'r73_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2500,14214,'','".AddSlashes(pg_result($resaco,0,'r73_selecaoponto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2500,14215,'','".AddSlashes(pg_result($resaco,0,'r73_rubric'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2500,14216,'','".AddSlashes(pg_result($resaco,0,'r73_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2500,14222,'','".AddSlashes(pg_result($resaco,0,'r73_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2500,14218,'','".AddSlashes(pg_result($resaco,0,'r73_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($r73_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update selecaopontorubricas set ";
     $virgula = "";
     if(trim($this->r73_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r73_sequencial"])){ 
       $sql  .= $virgula." r73_sequencial = $this->r73_sequencial ";
       $virgula = ",";
       if(trim($this->r73_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "r73_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r73_selecaoponto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r73_selecaoponto"])){ 
       $sql  .= $virgula." r73_selecaoponto = $this->r73_selecaoponto ";
       $virgula = ",";
       if(trim($this->r73_selecaoponto) == null ){ 
         $this->erro_sql = " Campo Sele��o Ponto nao Informado.";
         $this->erro_campo = "r73_selecaoponto";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r73_rubric)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r73_rubric"])){ 
       $sql  .= $virgula." r73_rubric = '$this->r73_rubric' ";
       $virgula = ",";
       if(trim($this->r73_rubric) == null ){ 
         $this->erro_sql = " Campo C�digo da Rubrica nao Informado.";
         $this->erro_campo = "r73_rubric";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r73_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r73_instit"])){ 
       $sql  .= $virgula." r73_instit = $this->r73_instit ";
       $virgula = ",";
       if(trim($this->r73_instit) == null ){ 
         $this->erro_sql = " Campo Codigo da Institui��o nao Informado.";
         $this->erro_campo = "r73_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r73_tipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r73_tipo"])){ 
       $sql  .= $virgula." r73_tipo = $this->r73_tipo ";
       $virgula = ",";
       if(trim($this->r73_tipo) == null ){ 
         $this->erro_sql = " Campo Tipo de Valor nao Informado.";
         $this->erro_campo = "r73_tipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r73_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r73_valor"])){ 
        if(trim($this->r73_valor)=="" && isset($GLOBALS["HTTP_POST_VARS"]["r73_valor"])){ 
           $this->r73_valor = "null" ; 
        } 
       $sql  .= $virgula." r73_valor = $this->r73_valor ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($r73_sequencial!=null){
       $sql .= " r73_sequencial = $this->r73_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->r73_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,14213,'$this->r73_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r73_sequencial"]) || $this->r73_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,2500,14213,'".AddSlashes(pg_result($resaco,$conresaco,'r73_sequencial'))."','$this->r73_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r73_selecaoponto"]) || $this->r73_selecaoponto != "")
           $resac = db_query("insert into db_acount values($acount,2500,14214,'".AddSlashes(pg_result($resaco,$conresaco,'r73_selecaoponto'))."','$this->r73_selecaoponto',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r73_rubric"]) || $this->r73_rubric != "")
           $resac = db_query("insert into db_acount values($acount,2500,14215,'".AddSlashes(pg_result($resaco,$conresaco,'r73_rubric'))."','$this->r73_rubric',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r73_instit"]) || $this->r73_instit != "")
           $resac = db_query("insert into db_acount values($acount,2500,14216,'".AddSlashes(pg_result($resaco,$conresaco,'r73_instit'))."','$this->r73_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r73_tipo"]) || $this->r73_tipo != "")
           $resac = db_query("insert into db_acount values($acount,2500,14222,'".AddSlashes(pg_result($resaco,$conresaco,'r73_tipo'))."','$this->r73_tipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r73_valor"]) || $this->r73_valor != "")
           $resac = db_query("insert into db_acount values($acount,2500,14218,'".AddSlashes(pg_result($resaco,$conresaco,'r73_valor'))."','$this->r73_valor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Configura��o das Rubricas do Ponto por Sele��o nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->r73_sequencial;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Configura��o das Rubricas do Ponto por Sele��o nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->r73_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->r73_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($r73_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($r73_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,14213,'$r73_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2500,14213,'','".AddSlashes(pg_result($resaco,$iresaco,'r73_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2500,14214,'','".AddSlashes(pg_result($resaco,$iresaco,'r73_selecaoponto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2500,14215,'','".AddSlashes(pg_result($resaco,$iresaco,'r73_rubric'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2500,14216,'','".AddSlashes(pg_result($resaco,$iresaco,'r73_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2500,14222,'','".AddSlashes(pg_result($resaco,$iresaco,'r73_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2500,14218,'','".AddSlashes(pg_result($resaco,$iresaco,'r73_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from selecaopontorubricas
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($r73_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " r73_sequencial = $r73_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Configura��o das Rubricas do Ponto por Sele��o nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$r73_sequencial;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Configura��o das Rubricas do Ponto por Sele��o nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$r73_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$r73_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:selecaopontorubricas";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $r73_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from selecaopontorubricas ";
     $sql .= "      inner join rhrubricas  on  rhrubricas.rh27_rubric = selecaopontorubricas.r73_rubric and  rhrubricas.rh27_instit = selecaopontorubricas.r73_instit";
     $sql .= "      inner join selecaoponto  on  selecaoponto.r72_sequencial = selecaopontorubricas.r73_selecaoponto";
     $sql .= "      inner join selecaopontorubricastipo  on  selecaopontorubricastipo.r74_sequencial = selecaopontorubricas.r73_tipo";
     $sql .= "      inner join db_config  on  db_config.codigo = rhrubricas.rh27_instit";
     $sql .= "      inner join rhtipomedia  on  rhtipomedia.rh29_tipo = rhrubricas.rh27_calc1";
     $sql .= "      inner join selecao  as a on   a.r44_selec = selecaoponto.r72_selecao and   a.r44_instit = selecaoponto.r72_instit";
     $sql2 = "";
     if($dbwhere==""){
       if($r73_sequencial!=null ){
         $sql2 .= " where selecaopontorubricas.r73_sequencial = $r73_sequencial "; 
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
   function sql_query_file ( $r73_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from selecaopontorubricas ";
     $sql2 = "";
     if($dbwhere==""){
       if($r73_sequencial!=null ){
         $sql2 .= " where selecaopontorubricas.r73_sequencial = $r73_sequencial "; 
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