<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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

//MODULO: projetos
//CLASSE DA ENTIDADE obrasconstr
class cl_obrasconstr { 
   // cria variaveis de erro 
   var $rotulo     = null; 
   var $query_sql  = null; 
   var $numrows    = 0; 
   var $erro_status= null; 
   var $erro_sql   = null; 
   var $erro_banco = null;  
   var $erro_msg   = null;  
   var $erro_campo = null;  
   var $pagina_retorno = null; 
   // cria variaveis do arquivo 
   var $ob08_codconstr = 0; 
   var $ob08_codobra = 0; 
   var $ob08_ocupacao = 0; 
   var $ob08_tipoconstr = 0; 
   var $ob08_area = 0; 
   var $ob08_tipolanc = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ob08_codconstr = int4 = Código da construção 
                 ob08_codobra = int4 = Código da obra 
                 ob08_ocupacao = int4 = Ocupação 
                 ob08_tipoconstr = int4 = Tipo de Construção 
                 ob08_area = float8 = Área 
                 ob08_tipolanc = int4 = Tipo de Lançamento 
                 ";
   //funcao construtor da classe 
   function cl_obrasconstr() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("obrasconstr"); 
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
       $this->ob08_codconstr = ($this->ob08_codconstr == ""?@$GLOBALS["HTTP_POST_VARS"]["ob08_codconstr"]:$this->ob08_codconstr);
       $this->ob08_codobra = ($this->ob08_codobra == ""?@$GLOBALS["HTTP_POST_VARS"]["ob08_codobra"]:$this->ob08_codobra);
       $this->ob08_ocupacao = ($this->ob08_ocupacao == ""?@$GLOBALS["HTTP_POST_VARS"]["ob08_ocupacao"]:$this->ob08_ocupacao);
       $this->ob08_tipoconstr = ($this->ob08_tipoconstr == ""?@$GLOBALS["HTTP_POST_VARS"]["ob08_tipoconstr"]:$this->ob08_tipoconstr);
       $this->ob08_area = ($this->ob08_area == ""?@$GLOBALS["HTTP_POST_VARS"]["ob08_area"]:$this->ob08_area);
       $this->ob08_tipolanc = ($this->ob08_tipolanc == ""?@$GLOBALS["HTTP_POST_VARS"]["ob08_tipolanc"]:$this->ob08_tipolanc);
     }else{
       $this->ob08_codconstr = ($this->ob08_codconstr == ""?@$GLOBALS["HTTP_POST_VARS"]["ob08_codconstr"]:$this->ob08_codconstr);
     }
   }
   // funcao para inclusao
   function incluir ($ob08_codconstr){ 
      $this->atualizacampos();
     if($this->ob08_codobra == null ){ 
       $this->erro_sql = " Campo Código da obra nao Informado.";
       $this->erro_campo = "ob08_codobra";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ob08_ocupacao == null ){ 
       $this->erro_sql = " Campo Ocupação nao Informado.";
       $this->erro_campo = "ob08_ocupacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ob08_tipoconstr == null ){ 
       $this->erro_sql = " Campo Tipo de Construção nao Informado.";
       $this->erro_campo = "ob08_tipoconstr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ob08_area == null ){ 
       $this->erro_sql = " Campo Área nao Informado.";
       $this->erro_campo = "ob08_area";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ob08_tipolanc == null ){ 
       $this->erro_sql = " Campo Tipo de Lançamento nao Informado.";
       $this->erro_campo = "ob08_tipolanc";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ob08_codconstr == "" || $ob08_codconstr == null ){
       $result = db_query("select nextval('obrasconstr_ob08_codconstr_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: obrasconstr_ob08_codconstr_seq do campo: ob08_codconstr"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ob08_codconstr = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from obrasconstr_ob08_codconstr_seq");
       if(($result != false) && (pg_result($result,0,0) < $ob08_codconstr)){
         $this->erro_sql = " Campo ob08_codconstr maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ob08_codconstr = $ob08_codconstr; 
       }
     }
     if(($this->ob08_codconstr == null) || ($this->ob08_codconstr == "") ){ 
       $this->erro_sql = " Campo ob08_codconstr nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into obrasconstr(
                                       ob08_codconstr 
                                      ,ob08_codobra 
                                      ,ob08_ocupacao 
                                      ,ob08_tipoconstr 
                                      ,ob08_area 
                                      ,ob08_tipolanc 
                       )
                values (
                                $this->ob08_codconstr 
                               ,$this->ob08_codobra 
                               ,$this->ob08_ocupacao 
                               ,$this->ob08_tipoconstr 
                               ,$this->ob08_area 
                               ,$this->ob08_tipolanc 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "construções da obra ($this->ob08_codconstr) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "construções da obra já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "construções da obra ($this->ob08_codconstr) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ob08_codconstr;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ob08_codconstr));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,5936,'$this->ob08_codconstr','I')");
       $resac = db_query("insert into db_acount values($acount,953,5936,'','".AddSlashes(pg_result($resaco,0,'ob08_codconstr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,953,5967,'','".AddSlashes(pg_result($resaco,0,'ob08_codobra'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,953,5968,'','".AddSlashes(pg_result($resaco,0,'ob08_ocupacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,953,5969,'','".AddSlashes(pg_result($resaco,0,'ob08_tipoconstr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,953,5970,'','".AddSlashes(pg_result($resaco,0,'ob08_area'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,953,5971,'','".AddSlashes(pg_result($resaco,0,'ob08_tipolanc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ob08_codconstr=null) { 
      $this->atualizacampos();
     $sql = " update obrasconstr set ";
     $virgula = "";
     if(trim($this->ob08_codconstr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ob08_codconstr"])){ 
       $sql  .= $virgula." ob08_codconstr = $this->ob08_codconstr ";
       $virgula = ",";
       if(trim($this->ob08_codconstr) == null ){ 
         $this->erro_sql = " Campo Código da construção nao Informado.";
         $this->erro_campo = "ob08_codconstr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ob08_codobra)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ob08_codobra"])){ 
       $sql  .= $virgula." ob08_codobra = $this->ob08_codobra ";
       $virgula = ",";
       if(trim($this->ob08_codobra) == null ){ 
         $this->erro_sql = " Campo Código da obra nao Informado.";
         $this->erro_campo = "ob08_codobra";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ob08_ocupacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ob08_ocupacao"])){ 
       $sql  .= $virgula." ob08_ocupacao = $this->ob08_ocupacao ";
       $virgula = ",";
       if(trim($this->ob08_ocupacao) == null ){ 
         $this->erro_sql = " Campo Ocupação nao Informado.";
         $this->erro_campo = "ob08_ocupacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ob08_tipoconstr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ob08_tipoconstr"])){ 
       $sql  .= $virgula." ob08_tipoconstr = $this->ob08_tipoconstr ";
       $virgula = ",";
       if(trim($this->ob08_tipoconstr) == null ){ 
         $this->erro_sql = " Campo Tipo de Construção nao Informado.";
         $this->erro_campo = "ob08_tipoconstr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ob08_area)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ob08_area"])){ 
       $sql  .= $virgula." ob08_area = $this->ob08_area ";
       $virgula = ",";
       if(trim($this->ob08_area) == null ){ 
         $this->erro_sql = " Campo Área nao Informado.";
         $this->erro_campo = "ob08_area";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ob08_tipolanc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ob08_tipolanc"])){ 
       $sql  .= $virgula." ob08_tipolanc = $this->ob08_tipolanc ";
       $virgula = ",";
       if(trim($this->ob08_tipolanc) == null ){ 
         $this->erro_sql = " Campo Tipo de Lançamento nao Informado.";
         $this->erro_campo = "ob08_tipolanc";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where  ob08_codconstr = $this->ob08_codconstr
";
     $resaco = $this->sql_record($this->sql_query_file($this->ob08_codconstr));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5936,'$this->ob08_codconstr','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ob08_codconstr"]))
           $resac = db_query("insert into db_acount values($acount,953,5936,'".AddSlashes(pg_result($resaco,$conresaco,'ob08_codconstr'))."','$this->ob08_codconstr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ob08_codobra"]))
           $resac = db_query("insert into db_acount values($acount,953,5967,'".AddSlashes(pg_result($resaco,$conresaco,'ob08_codobra'))."','$this->ob08_codobra',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ob08_ocupacao"]))
           $resac = db_query("insert into db_acount values($acount,953,5968,'".AddSlashes(pg_result($resaco,$conresaco,'ob08_ocupacao'))."','$this->ob08_ocupacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ob08_tipoconstr"]))
           $resac = db_query("insert into db_acount values($acount,953,5969,'".AddSlashes(pg_result($resaco,$conresaco,'ob08_tipoconstr'))."','$this->ob08_tipoconstr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ob08_area"]))
           $resac = db_query("insert into db_acount values($acount,953,5970,'".AddSlashes(pg_result($resaco,$conresaco,'ob08_area'))."','$this->ob08_area',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ob08_tipolanc"]))
           $resac = db_query("insert into db_acount values($acount,953,5971,'".AddSlashes(pg_result($resaco,$conresaco,'ob08_tipolanc'))."','$this->ob08_tipolanc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "construções da obra nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ob08_codconstr;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "construções da obra nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ob08_codconstr;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ob08_codconstr;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ob08_codconstr=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ob08_codconstr));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5936,'$ob08_codconstr','E')");
         $resac = db_query("insert into db_acount values($acount,953,5936,'','".AddSlashes(pg_result($resaco,$iresaco,'ob08_codconstr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,953,5967,'','".AddSlashes(pg_result($resaco,$iresaco,'ob08_codobra'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,953,5968,'','".AddSlashes(pg_result($resaco,$iresaco,'ob08_ocupacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,953,5969,'','".AddSlashes(pg_result($resaco,$iresaco,'ob08_tipoconstr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,953,5970,'','".AddSlashes(pg_result($resaco,$iresaco,'ob08_area'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,953,5971,'','".AddSlashes(pg_result($resaco,$iresaco,'ob08_tipolanc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from obrasconstr
                    where ";
     $sql2 = "";
        if($ob08_codconstr != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ob08_codconstr = $ob08_codconstr ";
        }
     $result = @pg_exec($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "construções da obra nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ob08_codconstr;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "construções da obra nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ob08_codconstr;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ob08_codconstr;
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
        $this->erro_sql   = "Record Vazio na Tabela:obrasconstr";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $ob08_codconstr=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from obrasconstr ";
     $sql .= "      inner join caracter  on  caracter.j31_codigo = obrasconstr.ob08_ocupacao";
     $sql .= "      inner join obras  on  obras.ob01_codobra = obrasconstr.ob08_codobra";
     $sql .= "      inner join cargrup  on  cargrup.j32_grupo = caracter.j31_grupo";
     $sql .= "      inner join obrastiporesp  on  obrastiporesp.ob02_cod = obras.ob01_tiporesp";
     $sql .= "      inner join obraspropri  on  obras.ob01_codobra = obraspropri.ob03_codobra";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = obraspropri.ob03_numcgm";
     $sql2 = "";
     if($dbwhere==""){
       if($ob08_codconstr!=null ){
         $sql2 .= " where obrasconstr.ob08_codconstr = $ob08_codconstr "; 
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

   function sql_query_file ( $ob08_codconstr=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from obrasconstr ";
     $sql2 = "";
     if($dbwhere==""){
       if($ob08_codconstr!=null ){
         $sql2 .= " where obrasconstr.ob08_codconstr = $ob08_codconstr "; 
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
   function sql_query_alvara ( $ob08_codconstr=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from obrasconstr ";
     $sql .= "      inner join caracter  on  caracter.j31_codigo = obrasconstr.ob08_ocupacao";
     $sql .= "      inner join obras  on  obras.ob01_codobra = obrasconstr.ob08_codobra";
     $sql .= "      inner join cargrup  on  cargrup.j32_grupo = caracter.j31_grupo";
     $sql .= "      inner join obrastiporesp  on  obrastiporesp.ob02_cod = obras.ob01_tiporesp";
     $sql .= "      inner join obrasalvara  on  obrasalvara.ob04_codobra = obrasconstr.ob08_codobra";
     $sql2 = "";
     if($dbwhere==""){
       if($ob08_codconstr!=null ){
         $sql2 .= " where obrasconstr.ob08_codconstr = $ob08_codconstr "; 
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
   function sql_query_ender ( $ob08_codconstr=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from obrasconstr ";
     $sql .= "      inner join caracter a on a.j31_codigo = obrasconstr.ob08_ocupacao";
     $sql .= "      inner join caracter b on b.j31_codigo = obrasconstr.ob08_tipoconstr";
     $sql .= "      inner join caracter c on c.j31_codigo = obrasconstr.ob08_tipolanc";
     $sql .= "      inner join cargrup a1 on  a1.j32_grupo = a.j31_grupo";
     $sql .= "      inner join cargrup b1 on  b1.j32_grupo = b.j31_grupo";
     $sql .= "      inner join cargrup c1 on  c1.j32_grupo = c.j31_grupo";
     $sql .= "      inner join obras  on  obras.ob01_codobra = obrasconstr.ob08_codobra";
     $sql .= "      inner join obrastiporesp  on  obrastiporesp.ob02_cod = obras.ob01_tiporesp";
     $sql .= "      inner join obraspropri  on  obras.ob01_codobra = obraspropri.ob03_codobra";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = obraspropri.ob03_numcgm";
     $sql .= "      inner join obrasender on obrasender.ob07_codconstr = obrasconstr.ob08_codconstr";
     $sql .= "      inner join ruas on ruas.j14_codigo = obrasender.ob07_lograd";
     $sql .= "      inner join bairro on bairro.j13_codi = obrasender.ob07_bairro";
     $sql .= "      left join obrashabite on obrashabite.ob09_codconstr = obrasconstr.ob08_codconstr";
     $sql2 = "";
     if($dbwhere==""){
       if($ob08_codconstr!=null ){
         $sql2 .= " where obrasconstr.ob08_codconstr = $ob08_codconstr "; 
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

	function sql_query_alvara_habite ( $ob08_codconstr=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from obrasconstr ";
     $sql .= "      inner join caracter  on  caracter.j31_codigo = obrasconstr.ob08_ocupacao";
     $sql .= "      inner join obras  on  obras.ob01_codobra = obrasconstr.ob08_codobra";
     $sql .= "      inner join cargrup  on  cargrup.j32_grupo = caracter.j31_grupo";
     $sql .= "      inner join obrastiporesp  on  obrastiporesp.ob02_cod = obras.ob01_tiporesp";
     $sql .= "      inner join obrasalvara    on  obrasalvara.ob04_codobra = obrasconstr.ob08_codobra";
		 $sql .= "      left  join obrashabite    on obrashabite.ob09_codconstr = obrasconstr.ob08_codconstr";
     $sql2 = "";
     if($dbwhere==""){
       if($ob08_codconstr!=null ){
         $sql2 .= " where obrasconstr.ob08_codconstr = $ob08_codconstr "; 
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
  
  /**
   * Sql para buscar as caracteristicas da obra 
   * @param integer $iCodigoObra
   */
  function sql_query_caracteristicasConstrucao($iCodigoObra) {
  	
  	$sSql = "select obrasconstr.*,                                                                                  ";
    $sSql.= "       ocupacao.j31_descr      as ocupacao,                                                            ";
    $sSql.= "       construcao.j31_descr    as construcao,                                                          ";
    $sSql.= "       tipolacamento.j31_descr as tipo_lancamento                                                      ";
    $sSql.= "  from obrasconstr                                                                                     ";
    $sSql.= "       inner join caracter as ocupacao       on ocupacao.j31_codigo      = obrasconstr.ob08_ocupacao   ";
    $sSql.= "       inner join caracter as construcao     on construcao.j31_codigo    = obrasconstr.ob08_tipoconstr ";
    $sSql.= "       inner join caracter as tipolacamento  on tipolacamento.j31_codigo = obrasconstr.ob08_tipolanc   ";
    $sSql.= " where ob08_codobra = {$iCodigoObra}                                                                   ";
    return  $sSql;
  }
}
?>