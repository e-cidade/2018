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

//MODULO: caixa
//CLASSE DA ENTIDADE debcontapedidoexc
class cl_debcontapedidoexc { 
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
   var $d77_sequencial = 0; 
   var $d77_dtexc_dia = null; 
   var $d77_dtexc_mes = null; 
   var $d77_dtexc_ano = null; 
   var $d77_dtexc = null; 
   var $d77_arqexc = null; 
   var $d77_debcontapedido = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 d77_sequencial = int4 = Sequencial 
                 d77_dtexc = date = Data da exclusao 
                 d77_arqexc = varchar(100) = Descricao do arquivo de exclusao 
                 d77_debcontapedido = int4 = Codigo sequencial 
                 ";
   //funcao construtor da classe 
   function cl_debcontapedidoexc() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("debcontapedidoexc"); 
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
       $this->d77_sequencial = ($this->d77_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["d77_sequencial"]:$this->d77_sequencial);
       if($this->d77_dtexc == ""){
         $this->d77_dtexc_dia = ($this->d77_dtexc_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["d77_dtexc_dia"]:$this->d77_dtexc_dia);
         $this->d77_dtexc_mes = ($this->d77_dtexc_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["d77_dtexc_mes"]:$this->d77_dtexc_mes);
         $this->d77_dtexc_ano = ($this->d77_dtexc_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["d77_dtexc_ano"]:$this->d77_dtexc_ano);
         if($this->d77_dtexc_dia != ""){
            $this->d77_dtexc = $this->d77_dtexc_ano."-".$this->d77_dtexc_mes."-".$this->d77_dtexc_dia;
         }
       }
       $this->d77_arqexc = ($this->d77_arqexc == ""?@$GLOBALS["HTTP_POST_VARS"]["d77_arqexc"]:$this->d77_arqexc);
       $this->d77_debcontapedido = ($this->d77_debcontapedido == ""?@$GLOBALS["HTTP_POST_VARS"]["d77_debcontapedido"]:$this->d77_debcontapedido);
     }else{
       $this->d77_sequencial = ($this->d77_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["d77_sequencial"]:$this->d77_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($d77_sequencial){ 
      $this->atualizacampos();
     if($this->d77_dtexc == null ){ 
       $this->erro_sql = " Campo Data da exclusao nao Informado.";
       $this->erro_campo = "d77_dtexc_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->d77_arqexc == null ){ 
       $this->erro_sql = " Campo Descricao do arquivo de exclusao nao Informado.";
       $this->erro_campo = "d77_arqexc";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->d77_debcontapedido == null ){ 
       $this->erro_sql = " Campo Codigo sequencial nao Informado.";
       $this->erro_campo = "d77_debcontapedido";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($d77_sequencial == "" || $d77_sequencial == null ){
       $result = db_query("select nextval('debcontapedidoexc_d77_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: debcontapedidoexc_d77_sequencial_seq do campo: d77_sequencial"; 
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->d77_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from debcontapedidoexc_d77_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $d77_sequencial)){
         $this->erro_sql = " Campo d77_sequencial maior que �ltimo n�mero da sequencia.";
         $this->erro_banco = "Sequencia menor que este n�mero.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->d77_sequencial = $d77_sequencial; 
       }
     }
     if(($this->d77_sequencial == null) || ($this->d77_sequencial == "") ){ 
       $this->erro_sql = " Campo d77_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into debcontapedidoexc(
                                       d77_sequencial 
                                      ,d77_dtexc 
                                      ,d77_arqexc 
                                      ,d77_debcontapedido 
                       )
                values (
                                $this->d77_sequencial 
                               ,".($this->d77_dtexc == "null" || $this->d77_dtexc == ""?"null":"'".$this->d77_dtexc."'")." 
                               ,'$this->d77_arqexc' 
                               ,$this->d77_debcontapedido 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Exclusao do pedido de debito em conta ($this->d77_sequencial) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Exclusao do pedido de debito em conta j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Exclusao do pedido de debito em conta ($this->d77_sequencial) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->d77_sequencial;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->d77_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,8260,'$this->d77_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,1391,8260,'','".AddSlashes(pg_result($resaco,0,'d77_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1391,8258,'','".AddSlashes(pg_result($resaco,0,'d77_dtexc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1391,8261,'','".AddSlashes(pg_result($resaco,0,'d77_arqexc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1391,8262,'','".AddSlashes(pg_result($resaco,0,'d77_debcontapedido'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($d77_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update debcontapedidoexc set ";
     $virgula = "";
     if(trim($this->d77_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["d77_sequencial"])){ 
       $sql  .= $virgula." d77_sequencial = $this->d77_sequencial ";
       $virgula = ",";
       if(trim($this->d77_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "d77_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->d77_dtexc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["d77_dtexc_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["d77_dtexc_dia"] !="") ){ 
       $sql  .= $virgula." d77_dtexc = '$this->d77_dtexc' ";
       $virgula = ",";
       if(trim($this->d77_dtexc) == null ){ 
         $this->erro_sql = " Campo Data da exclusao nao Informado.";
         $this->erro_campo = "d77_dtexc_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["d77_dtexc_dia"])){ 
         $sql  .= $virgula." d77_dtexc = null ";
         $virgula = ",";
         if(trim($this->d77_dtexc) == null ){ 
           $this->erro_sql = " Campo Data da exclusao nao Informado.";
           $this->erro_campo = "d77_dtexc_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->d77_arqexc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["d77_arqexc"])){ 
       $sql  .= $virgula." d77_arqexc = '$this->d77_arqexc' ";
       $virgula = ",";
       if(trim($this->d77_arqexc) == null ){ 
         $this->erro_sql = " Campo Descricao do arquivo de exclusao nao Informado.";
         $this->erro_campo = "d77_arqexc";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->d77_debcontapedido)!="" || isset($GLOBALS["HTTP_POST_VARS"]["d77_debcontapedido"])){ 
       $sql  .= $virgula." d77_debcontapedido = $this->d77_debcontapedido ";
       $virgula = ",";
       if(trim($this->d77_debcontapedido) == null ){ 
         $this->erro_sql = " Campo Codigo sequencial nao Informado.";
         $this->erro_campo = "d77_debcontapedido";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($d77_sequencial!=null){
       $sql .= " d77_sequencial = $this->d77_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->d77_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,8260,'$this->d77_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["d77_sequencial"]))
           $resac = db_query("insert into db_acount values($acount,1391,8260,'".AddSlashes(pg_result($resaco,$conresaco,'d77_sequencial'))."','$this->d77_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["d77_dtexc"]))
           $resac = db_query("insert into db_acount values($acount,1391,8258,'".AddSlashes(pg_result($resaco,$conresaco,'d77_dtexc'))."','$this->d77_dtexc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["d77_arqexc"]))
           $resac = db_query("insert into db_acount values($acount,1391,8261,'".AddSlashes(pg_result($resaco,$conresaco,'d77_arqexc'))."','$this->d77_arqexc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["d77_debcontapedido"]))
           $resac = db_query("insert into db_acount values($acount,1391,8262,'".AddSlashes(pg_result($resaco,$conresaco,'d77_debcontapedido'))."','$this->d77_debcontapedido',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Exclusao do pedido de debito em conta nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->d77_sequencial;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Exclusao do pedido de debito em conta nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->d77_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->d77_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($d77_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($d77_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,8260,'$d77_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,1391,8260,'','".AddSlashes(pg_result($resaco,$iresaco,'d77_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1391,8258,'','".AddSlashes(pg_result($resaco,$iresaco,'d77_dtexc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1391,8261,'','".AddSlashes(pg_result($resaco,$iresaco,'d77_arqexc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1391,8262,'','".AddSlashes(pg_result($resaco,$iresaco,'d77_debcontapedido'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from debcontapedidoexc
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($d77_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " d77_sequencial = $d77_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Exclusao do pedido de debito em conta nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$d77_sequencial;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Exclusao do pedido de debito em conta nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$d77_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$d77_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:debcontapedidoexc";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
}
?>