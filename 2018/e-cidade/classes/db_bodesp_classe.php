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

//MODULO: teleatend
//CLASSE DA ENTIDADE bodesp
class cl_bodesp { 
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
   var $bo05_cod_desp = 0; 
   var $bo05_codmov = 0; 
   var $bo05_datadesp_dia = null; 
   var $bo05_datadesp_mes = null; 
   var $bo05_datadesp_ano = null; 
   var $bo05_datadesp = null; 
   var $bo05_coddepto_ori = 0; 
   var $bo05_coddepto_dest = 0; 
   var $bo05_despacho = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 bo05_cod_desp = int4 = Código do Despacho 
                 bo05_codmov = int4 = Número do movimento 
                 bo05_datadesp = date = Data do despacho 
                 bo05_coddepto_ori = int4 = Departamento de Origem 
                 bo05_coddepto_dest = int4 = Departamento de Destino 
                 bo05_despacho = text = Despacho 
                 ";
   //funcao construtor da classe 
   function cl_bodesp() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("bodesp"); 
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
       $this->bo05_cod_desp = ($this->bo05_cod_desp == ""?@$GLOBALS["HTTP_POST_VARS"]["bo05_cod_desp"]:$this->bo05_cod_desp);
       $this->bo05_codmov = ($this->bo05_codmov == ""?@$GLOBALS["HTTP_POST_VARS"]["bo05_codmov"]:$this->bo05_codmov);
       if($this->bo05_datadesp == ""){
         $this->bo05_datadesp_dia = ($this->bo05_datadesp_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["bo05_datadesp_dia"]:$this->bo05_datadesp_dia);
         $this->bo05_datadesp_mes = ($this->bo05_datadesp_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["bo05_datadesp_mes"]:$this->bo05_datadesp_mes);
         $this->bo05_datadesp_ano = ($this->bo05_datadesp_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["bo05_datadesp_ano"]:$this->bo05_datadesp_ano);
         if($this->bo05_datadesp_dia != ""){
            $this->bo05_datadesp = $this->bo05_datadesp_ano."-".$this->bo05_datadesp_mes."-".$this->bo05_datadesp_dia;
         }
       }
       $this->bo05_coddepto_ori = ($this->bo05_coddepto_ori == ""?@$GLOBALS["HTTP_POST_VARS"]["bo05_coddepto_ori"]:$this->bo05_coddepto_ori);
       $this->bo05_coddepto_dest = ($this->bo05_coddepto_dest == ""?@$GLOBALS["HTTP_POST_VARS"]["bo05_coddepto_dest"]:$this->bo05_coddepto_dest);
       $this->bo05_despacho = ($this->bo05_despacho == ""?@$GLOBALS["HTTP_POST_VARS"]["bo05_despacho"]:$this->bo05_despacho);
     }else{
       $this->bo05_cod_desp = ($this->bo05_cod_desp == ""?@$GLOBALS["HTTP_POST_VARS"]["bo05_cod_desp"]:$this->bo05_cod_desp);
     }
   }
   // funcao para inclusao
   function incluir ($bo05_cod_desp){ 
      $this->atualizacampos();
     if($this->bo05_codmov == null ){ 
       $this->erro_sql = " Campo Número do movimento nao Informado.";
       $this->erro_campo = "bo05_codmov";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->bo05_datadesp == null ){ 
       $this->erro_sql = " Campo Data do despacho nao Informado.";
       $this->erro_campo = "bo05_datadesp_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->bo05_coddepto_ori == null ){ 
       $this->erro_sql = " Campo Departamento de Origem nao Informado.";
       $this->erro_campo = "bo05_coddepto_ori";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->bo05_coddepto_dest == null ){ 
       $this->erro_sql = " Campo Departamento de Destino nao Informado.";
       $this->erro_campo = "bo05_coddepto_dest";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->bo05_despacho == null ){ 
       $this->erro_sql = " Campo Despacho nao Informado.";
       $this->erro_campo = "bo05_despacho";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($bo05_cod_desp == "" || $bo05_cod_desp == null ){
       $result = db_query("select nextval('tel_coddesp_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: tel_coddesp_seq do campo: bo05_cod_desp"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->bo05_cod_desp = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from tel_coddesp_seq");
       if(($result != false) && (pg_result($result,0,0) < $bo05_cod_desp)){
         $this->erro_sql = " Campo bo05_cod_desp maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->bo05_cod_desp = $bo05_cod_desp; 
       }
     }
     if(($this->bo05_cod_desp == null) || ($this->bo05_cod_desp == "") ){ 
       $this->erro_sql = " Campo bo05_cod_desp nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into bodesp(
                                       bo05_cod_desp 
                                      ,bo05_codmov 
                                      ,bo05_datadesp 
                                      ,bo05_coddepto_ori 
                                      ,bo05_coddepto_dest 
                                      ,bo05_despacho 
                       )
                values (
                                $this->bo05_cod_desp 
                               ,$this->bo05_codmov 
                               ,".($this->bo05_datadesp == "null" || $this->bo05_datadesp == ""?"null":"'".$this->bo05_datadesp."'")." 
                               ,$this->bo05_coddepto_ori 
                               ,$this->bo05_coddepto_dest 
                               ,'$this->bo05_despacho' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Despacho ($this->bo05_cod_desp) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Despacho já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Despacho ($this->bo05_cod_desp) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->bo05_cod_desp;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->bo05_cod_desp));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,8596,'$this->bo05_cod_desp','I')");
       $resac = db_query("insert into db_acount values($acount,1462,8596,'','".AddSlashes(pg_result($resaco,0,'bo05_cod_desp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1462,8597,'','".AddSlashes(pg_result($resaco,0,'bo05_codmov'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1462,8598,'','".AddSlashes(pg_result($resaco,0,'bo05_datadesp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1462,8599,'','".AddSlashes(pg_result($resaco,0,'bo05_coddepto_ori'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1462,8600,'','".AddSlashes(pg_result($resaco,0,'bo05_coddepto_dest'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1462,8601,'','".AddSlashes(pg_result($resaco,0,'bo05_despacho'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($bo05_cod_desp=null) { 
      $this->atualizacampos();
     $sql = " update bodesp set ";
     $virgula = "";
     if(trim($this->bo05_cod_desp)!="" || isset($GLOBALS["HTTP_POST_VARS"]["bo05_cod_desp"])){ 
       $sql  .= $virgula." bo05_cod_desp = $this->bo05_cod_desp ";
       $virgula = ",";
       if(trim($this->bo05_cod_desp) == null ){ 
         $this->erro_sql = " Campo Código do Despacho nao Informado.";
         $this->erro_campo = "bo05_cod_desp";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->bo05_codmov)!="" || isset($GLOBALS["HTTP_POST_VARS"]["bo05_codmov"])){ 
       $sql  .= $virgula." bo05_codmov = $this->bo05_codmov ";
       $virgula = ",";
       if(trim($this->bo05_codmov) == null ){ 
         $this->erro_sql = " Campo Número do movimento nao Informado.";
         $this->erro_campo = "bo05_codmov";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->bo05_datadesp)!="" || isset($GLOBALS["HTTP_POST_VARS"]["bo05_datadesp_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["bo05_datadesp_dia"] !="") ){ 
       $sql  .= $virgula." bo05_datadesp = '$this->bo05_datadesp' ";
       $virgula = ",";
       if(trim($this->bo05_datadesp) == null ){ 
         $this->erro_sql = " Campo Data do despacho nao Informado.";
         $this->erro_campo = "bo05_datadesp_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["bo05_datadesp_dia"])){ 
         $sql  .= $virgula." bo05_datadesp = null ";
         $virgula = ",";
         if(trim($this->bo05_datadesp) == null ){ 
           $this->erro_sql = " Campo Data do despacho nao Informado.";
           $this->erro_campo = "bo05_datadesp_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->bo05_coddepto_ori)!="" || isset($GLOBALS["HTTP_POST_VARS"]["bo05_coddepto_ori"])){ 
       $sql  .= $virgula." bo05_coddepto_ori = $this->bo05_coddepto_ori ";
       $virgula = ",";
       if(trim($this->bo05_coddepto_ori) == null ){ 
         $this->erro_sql = " Campo Departamento de Origem nao Informado.";
         $this->erro_campo = "bo05_coddepto_ori";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->bo05_coddepto_dest)!="" || isset($GLOBALS["HTTP_POST_VARS"]["bo05_coddepto_dest"])){ 
       $sql  .= $virgula." bo05_coddepto_dest = $this->bo05_coddepto_dest ";
       $virgula = ",";
       if(trim($this->bo05_coddepto_dest) == null ){ 
         $this->erro_sql = " Campo Departamento de Destino nao Informado.";
         $this->erro_campo = "bo05_coddepto_dest";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->bo05_despacho)!="" || isset($GLOBALS["HTTP_POST_VARS"]["bo05_despacho"])){ 
       $sql  .= $virgula." bo05_despacho = '$this->bo05_despacho' ";
       $virgula = ",";
       if(trim($this->bo05_despacho) == null ){ 
         $this->erro_sql = " Campo Despacho nao Informado.";
         $this->erro_campo = "bo05_despacho";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($bo05_cod_desp!=null){
       $sql .= " bo05_cod_desp = $this->bo05_cod_desp";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->bo05_cod_desp));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,8596,'$this->bo05_cod_desp','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["bo05_cod_desp"]))
           $resac = db_query("insert into db_acount values($acount,1462,8596,'".AddSlashes(pg_result($resaco,$conresaco,'bo05_cod_desp'))."','$this->bo05_cod_desp',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["bo05_codmov"]))
           $resac = db_query("insert into db_acount values($acount,1462,8597,'".AddSlashes(pg_result($resaco,$conresaco,'bo05_codmov'))."','$this->bo05_codmov',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["bo05_datadesp"]))
           $resac = db_query("insert into db_acount values($acount,1462,8598,'".AddSlashes(pg_result($resaco,$conresaco,'bo05_datadesp'))."','$this->bo05_datadesp',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["bo05_coddepto_ori"]))
           $resac = db_query("insert into db_acount values($acount,1462,8599,'".AddSlashes(pg_result($resaco,$conresaco,'bo05_coddepto_ori'))."','$this->bo05_coddepto_ori',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["bo05_coddepto_dest"]))
           $resac = db_query("insert into db_acount values($acount,1462,8600,'".AddSlashes(pg_result($resaco,$conresaco,'bo05_coddepto_dest'))."','$this->bo05_coddepto_dest',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["bo05_despacho"]))
           $resac = db_query("insert into db_acount values($acount,1462,8601,'".AddSlashes(pg_result($resaco,$conresaco,'bo05_despacho'))."','$this->bo05_despacho',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Despacho nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->bo05_cod_desp;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Despacho nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->bo05_cod_desp;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->bo05_cod_desp;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($bo05_cod_desp=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($bo05_cod_desp));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,8596,'$bo05_cod_desp','E')");
         $resac = db_query("insert into db_acount values($acount,1462,8596,'','".AddSlashes(pg_result($resaco,$iresaco,'bo05_cod_desp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1462,8597,'','".AddSlashes(pg_result($resaco,$iresaco,'bo05_codmov'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1462,8598,'','".AddSlashes(pg_result($resaco,$iresaco,'bo05_datadesp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1462,8599,'','".AddSlashes(pg_result($resaco,$iresaco,'bo05_coddepto_ori'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1462,8600,'','".AddSlashes(pg_result($resaco,$iresaco,'bo05_coddepto_dest'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1462,8601,'','".AddSlashes(pg_result($resaco,$iresaco,'bo05_despacho'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from bodesp
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($bo05_cod_desp != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " bo05_cod_desp = $bo05_cod_desp ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Despacho nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$bo05_cod_desp;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Despacho nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$bo05_cod_desp;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$bo05_cod_desp;
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
        $this->erro_sql   = "Record Vazio na Tabela:bodesp";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $bo05_cod_desp=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from bodesp ";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = bodesp.bo05_coddepto_dest";
     $sql .= "      inner join bo  on  bo.bo01_codbo = bodesp.bo05_codbo";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = bo.bo01_numcgm";
     $sql .= "      inner join tipoproc  on  tipoproc.p51_codigo = bo.bo01_codtipo";
     $sql2 = "";
     if($dbwhere==""){
       if($bo05_cod_desp!=null ){
         $sql2 .= " where bodesp.bo05_cod_desp = $bo05_cod_desp "; 
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
   function sql_query_file ( $bo05_cod_desp=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from bodesp ";
     $sql2 = "";
     if($dbwhere==""){
       if($bo05_cod_desp!=null ){
         $sql2 .= " where bodesp.bo05_cod_desp = $bo05_cod_desp "; 
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