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

//MODULO: fiscal
//CLASSE DA ENTIDADE autotipo
class cl_autotipo { 
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
   var $y59_codigo = 0; 
   var $y59_codauto = 0; 
   var $y59_codtipo = 0; 
   var $y59_valor = 0; 
   var $y59_tipo = 0; 
   var $y59_fator = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 y59_codigo = int4 = Codigo Sequencial 
                 y59_codauto = int4 = Código do Auto de Infração 
                 y59_codtipo = int8 = Código da Procedência 
                 y59_valor = float8 = Valor 
                 y59_tipo = int4 = Tipo de Correção 
                 y59_fator = int4 = Fator 
                 ";
   //funcao construtor da classe 
   function cl_autotipo() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("autotipo"); 
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
       $this->y59_codigo = ($this->y59_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["y59_codigo"]:$this->y59_codigo);
       $this->y59_codauto = ($this->y59_codauto == ""?@$GLOBALS["HTTP_POST_VARS"]["y59_codauto"]:$this->y59_codauto);
       $this->y59_codtipo = ($this->y59_codtipo == ""?@$GLOBALS["HTTP_POST_VARS"]["y59_codtipo"]:$this->y59_codtipo);
       $this->y59_valor = ($this->y59_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["y59_valor"]:$this->y59_valor);
       $this->y59_tipo = ($this->y59_tipo == ""?@$GLOBALS["HTTP_POST_VARS"]["y59_tipo"]:$this->y59_tipo);
       $this->y59_fator = ($this->y59_fator == ""?@$GLOBALS["HTTP_POST_VARS"]["y59_fator"]:$this->y59_fator);
     }else{
       $this->y59_codigo = ($this->y59_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["y59_codigo"]:$this->y59_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($y59_codigo){ 
      $this->atualizacampos();
     if($this->y59_codauto == null ){ 
       $this->erro_sql = " Campo Código do Auto de Infração nao Informado.";
       $this->erro_campo = "y59_codauto";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->y59_codtipo == null ){ 
       $this->erro_sql = " Campo Código da Procedência nao Informado.";
       $this->erro_campo = "y59_codtipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->y59_valor == null ){ 
       $this->erro_sql = " Campo Valor nao Informado.";
       $this->erro_campo = "y59_valor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->y59_tipo == null ){ 
       $this->erro_sql = " Campo Tipo de Correção nao Informado.";
       $this->erro_campo = "y59_tipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->y59_fator == null ){ 
       $this->erro_sql = " Campo Fator nao Informado.";
       $this->erro_campo = "y59_fator";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($y59_codigo == "" || $y59_codigo == null ){
       $result = db_query("select nextval('autotipo_y59_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: autotipo_y59_codigo_seq do campo: y59_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->y59_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from autotipo_y59_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $y59_codigo)){
         $this->erro_sql = " Campo y59_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->y59_codigo = $y59_codigo; 
       }
     }
     if(($this->y59_codigo == null) || ($this->y59_codigo == "") ){ 
       $this->erro_sql = " Campo y59_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into autotipo(
                                       y59_codigo 
                                      ,y59_codauto 
                                      ,y59_codtipo 
                                      ,y59_valor 
                                      ,y59_tipo 
                                      ,y59_fator 
                       )
                values (
                                $this->y59_codigo 
                               ,$this->y59_codauto 
                               ,$this->y59_codtipo 
                               ,$this->y59_valor 
                               ,$this->y59_tipo 
                               ,$this->y59_fator 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "autotipo ($this->y59_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "autotipo já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "autotipo ($this->y59_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->y59_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->y59_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,6797,'$this->y59_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,700,6797,'','".AddSlashes(pg_result($resaco,0,'y59_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,700,4993,'','".AddSlashes(pg_result($resaco,0,'y59_codauto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,700,4994,'','".AddSlashes(pg_result($resaco,0,'y59_codtipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,700,6628,'','".AddSlashes(pg_result($resaco,0,'y59_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,700,6660,'','".AddSlashes(pg_result($resaco,0,'y59_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,700,6661,'','".AddSlashes(pg_result($resaco,0,'y59_fator'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($y59_codigo=null) { 
      $this->atualizacampos();
     $sql = " update autotipo set ";
     $virgula = "";
     if(trim($this->y59_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y59_codigo"])){ 
       $sql  .= $virgula." y59_codigo = $this->y59_codigo ";
       $virgula = ",";
       if(trim($this->y59_codigo) == null ){ 
         $this->erro_sql = " Campo Codigo Sequencial nao Informado.";
         $this->erro_campo = "y59_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->y59_codauto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y59_codauto"])){ 
       $sql  .= $virgula." y59_codauto = $this->y59_codauto ";
       $virgula = ",";
       if(trim($this->y59_codauto) == null ){ 
         $this->erro_sql = " Campo Código do Auto de Infração nao Informado.";
         $this->erro_campo = "y59_codauto";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->y59_codtipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y59_codtipo"])){ 
       $sql  .= $virgula." y59_codtipo = $this->y59_codtipo ";
       $virgula = ",";
       if(trim($this->y59_codtipo) == null ){ 
         $this->erro_sql = " Campo Código da Procedência nao Informado.";
         $this->erro_campo = "y59_codtipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->y59_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y59_valor"])){ 
       $sql  .= $virgula." y59_valor = $this->y59_valor ";
       $virgula = ",";
       if(trim($this->y59_valor) == null ){ 
         $this->erro_sql = " Campo Valor nao Informado.";
         $this->erro_campo = "y59_valor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->y59_tipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y59_tipo"])){ 
       $sql  .= $virgula." y59_tipo = $this->y59_tipo ";
       $virgula = ",";
       if(trim($this->y59_tipo) == null ){ 
         $this->erro_sql = " Campo Tipo de Correção nao Informado.";
         $this->erro_campo = "y59_tipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->y59_fator)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y59_fator"])){ 
       $sql  .= $virgula." y59_fator = $this->y59_fator ";
       $virgula = ",";
       if(trim($this->y59_fator) == null ){ 
         $this->erro_sql = " Campo Fator nao Informado.";
         $this->erro_campo = "y59_fator";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($y59_codigo!=null){
       $sql .= " y59_codigo = $this->y59_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->y59_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6797,'$this->y59_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["y59_codigo"]))
           $resac = db_query("insert into db_acount values($acount,700,6797,'".AddSlashes(pg_result($resaco,$conresaco,'y59_codigo'))."','$this->y59_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["y59_codauto"]))
           $resac = db_query("insert into db_acount values($acount,700,4993,'".AddSlashes(pg_result($resaco,$conresaco,'y59_codauto'))."','$this->y59_codauto',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["y59_codtipo"]))
           $resac = db_query("insert into db_acount values($acount,700,4994,'".AddSlashes(pg_result($resaco,$conresaco,'y59_codtipo'))."','$this->y59_codtipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["y59_valor"]))
           $resac = db_query("insert into db_acount values($acount,700,6628,'".AddSlashes(pg_result($resaco,$conresaco,'y59_valor'))."','$this->y59_valor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["y59_tipo"]))
           $resac = db_query("insert into db_acount values($acount,700,6660,'".AddSlashes(pg_result($resaco,$conresaco,'y59_tipo'))."','$this->y59_tipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["y59_fator"]))
           $resac = db_query("insert into db_acount values($acount,700,6661,'".AddSlashes(pg_result($resaco,$conresaco,'y59_fator'))."','$this->y59_fator',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "autotipo nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->y59_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "autotipo nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->y59_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->y59_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($y59_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($y59_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6797,'$y59_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,700,6797,'','".AddSlashes(pg_result($resaco,$iresaco,'y59_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,700,4993,'','".AddSlashes(pg_result($resaco,$iresaco,'y59_codauto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,700,4994,'','".AddSlashes(pg_result($resaco,$iresaco,'y59_codtipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,700,6628,'','".AddSlashes(pg_result($resaco,$iresaco,'y59_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,700,6660,'','".AddSlashes(pg_result($resaco,$iresaco,'y59_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,700,6661,'','".AddSlashes(pg_result($resaco,$iresaco,'y59_fator'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from autotipo
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($y59_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " y59_codigo = $y59_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "autotipo nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$y59_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "autotipo nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$y59_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$y59_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:autotipo";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $y59_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from autotipo ";
     $sql .= "      inner join fiscalproc  on  fiscalproc.y29_codtipo = autotipo.y59_codtipo";
     $sql .= "      inner join auto  on  auto.y50_codauto = autotipo.y59_codauto";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = fiscalproc.y29_coddepto";
     $sql .= "      inner join tipoandam  on  tipoandam.y41_codtipo = fiscalproc.y29_tipoandam";
     $sql .= "      inner join tipofiscaliza  on  tipofiscaliza.y27_codtipo = fiscalproc.y29_tipofisc";
     $sql .= "      inner join db_depart  as a on   a.coddepto = auto.y50_setor";
     $sql .= "      inner join tipofiscaliza  as b on   b.y27_codtipo = auto.y50_codtipo";
     $sql2 = "";
     if($dbwhere==""){
       if($y59_codigo!=null ){
         $sql2 .= " where autotipo.y59_codigo = $y59_codigo "; 
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
   function sql_query_baixa ( $y59_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from autotipo ";
     $sql .= "      inner join fiscalproc            on fiscalproc.y29_codtipo                   = autotipo.y59_codtipo                  ";
     $sql .= "      left  join autotipobaixa         on autotipobaixa.y86_codautotipo            = autotipo.y59_codigo                   ";
     $sql .= "      left  join autotipobaixaproc     on autotipobaixaproc.y87_baixaproc          = autotipobaixa.y86_codbaixaproc        ";
     $sql .= "      left  join autotipobaixaprocproc on autotipobaixaprocproc.y114_baixaproc     = autotipobaixaproc.y87_baixaproc       ";
     $sql .= "      left  join protprocesso          on protprocesso.p58_codproc                 = autotipobaixaprocproc.y114_processo   ";
     $sql2 = "";
     if($dbwhere==""){
       if($y59_codigo!=null ){
         $sql2 .= " where autotipo.y59_codigo = $y59_codigo "; 
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
   function sql_query_file ( $y59_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from autotipo ";
     $sql2 = "";
     if($dbwhere==""){
       if($y59_codigo!=null ){
         $sql2 .= " where autotipo.y59_codigo = $y59_codigo "; 
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
   function sql_query_rec ( $y59_codauto=null,$y59_codtipo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from autotipo ";
     $sql .= "      inner join fiscalproc  on  fiscalproc.y29_codtipo = autotipo.y59_codtipo";
     $sql .= "      inner join auto  on  auto.y50_codauto = autotipo.y59_codauto";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = fiscalproc.y29_coddepto";
     $sql .= "      inner join tipoandam  on  tipoandam.y41_codtipo = fiscalproc.y29_tipoandam";
     $sql .= "      inner join db_depart  as a on   a.coddepto = auto.y50_setor";
     $sql .= "      inner join fiscalprocrec on fiscalprocrec.y45_codtipo=fiscalproc.y29_codtipo";
     $sql2 = "";
     if($dbwhere==""){
       if($y59_codauto!=null ){
         $sql2 .= " where autotipo.y59_codauto = $y59_codauto ";
       }
       if($y59_codtipo!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         }
         $sql2 .= " autotipo.y59_codtipo = $y59_codtipo ";
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