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

//MODULO: inflatores
//CLASSE DA ENTIDADE infcab
class cl_infcab { 
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
   var $i03_codigo = 0; 
   var $i03_descr = null; 
   var $i03_numcgm = 0; 
   var $i03_dtbase_dia = null; 
   var $i03_dtbase_mes = null; 
   var $i03_dtbase_ano = null; 
   var $i03_dtbase = null; 
   var $i03_dtlanc_dia = null; 
   var $i03_dtlanc_mes = null; 
   var $i03_dtlanc_ano = null; 
   var $i03_dtlanc = null; 
   var $i03_id_usuario = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 i03_codigo = int8 = Código 
                 i03_descr = varchar(40) = Descrição 
                 i03_numcgm = int8 = Número do CGM 
                 i03_dtbase = date = Data Base 
                 i03_dtlanc = date = Data lançamento 
                 i03_id_usuario = int8 = Login 
                 ";
   //funcao construtor da classe 
   function cl_infcab() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("infcab"); 
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
       $this->i03_codigo = ($this->i03_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["i03_codigo"]:$this->i03_codigo);
       $this->i03_descr = ($this->i03_descr == ""?@$GLOBALS["HTTP_POST_VARS"]["i03_descr"]:$this->i03_descr);
       $this->i03_numcgm = ($this->i03_numcgm == ""?@$GLOBALS["HTTP_POST_VARS"]["i03_numcgm"]:$this->i03_numcgm);
       if($this->i03_dtbase == ""){
         $this->i03_dtbase_dia = ($this->i03_dtbase_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["i03_dtbase_dia"]:$this->i03_dtbase_dia);
         $this->i03_dtbase_mes = ($this->i03_dtbase_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["i03_dtbase_mes"]:$this->i03_dtbase_mes);
         $this->i03_dtbase_ano = ($this->i03_dtbase_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["i03_dtbase_ano"]:$this->i03_dtbase_ano);
         if($this->i03_dtbase_dia != ""){
            $this->i03_dtbase = $this->i03_dtbase_ano."-".$this->i03_dtbase_mes."-".$this->i03_dtbase_dia;
         }
       }
       if($this->i03_dtlanc == ""){
         $this->i03_dtlanc_dia = ($this->i03_dtlanc_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["i03_dtlanc_dia"]:$this->i03_dtlanc_dia);
         $this->i03_dtlanc_mes = ($this->i03_dtlanc_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["i03_dtlanc_mes"]:$this->i03_dtlanc_mes);
         $this->i03_dtlanc_ano = ($this->i03_dtlanc_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["i03_dtlanc_ano"]:$this->i03_dtlanc_ano);
         if($this->i03_dtlanc_dia != ""){
            $this->i03_dtlanc = $this->i03_dtlanc_ano."-".$this->i03_dtlanc_mes."-".$this->i03_dtlanc_dia;
         }
       }
       $this->i03_id_usuario = ($this->i03_id_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["i03_id_usuario"]:$this->i03_id_usuario);
     }else{
       $this->i03_codigo = ($this->i03_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["i03_codigo"]:$this->i03_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($i03_codigo){ 
      $this->atualizacampos();
     if($this->i03_descr == null ){ 
       $this->erro_sql = " Campo Descrição nao Informado.";
       $this->erro_campo = "i03_descr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->i03_numcgm == null ){ 
       $this->erro_sql = " Campo Número do CGM nao Informado.";
       $this->erro_campo = "i03_numcgm";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->i03_dtbase == null ){ 
       $this->erro_sql = " Campo Data Base nao Informado.";
       $this->erro_campo = "i03_dtbase_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->i03_dtlanc == null ){ 
       $this->erro_sql = " Campo Data lançamento nao Informado.";
       $this->erro_campo = "i03_dtlanc_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->i03_id_usuario == null ){ 
       $this->erro_sql = " Campo Login nao Informado.";
       $this->erro_campo = "i03_id_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($i03_codigo == "" || $i03_codigo == null ){
       $result = db_query("select nextval('infcab_i03_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: infcab_i03_codigo_seq do campo: i03_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->i03_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from infcab_i03_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $i03_codigo)){
         $this->erro_sql = " Campo i03_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->i03_codigo = $i03_codigo; 
       }
     }
     if(($this->i03_codigo == null) || ($this->i03_codigo == "") ){ 
       $this->erro_sql = " Campo i03_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into infcab(
                                       i03_codigo 
                                      ,i03_descr 
                                      ,i03_numcgm 
                                      ,i03_dtbase 
                                      ,i03_dtlanc 
                                      ,i03_id_usuario 
                       )
                values (
                                $this->i03_codigo 
                               ,'$this->i03_descr' 
                               ,$this->i03_numcgm 
                               ,".($this->i03_dtbase == "null" || $this->i03_dtbase == ""?"null":"'".$this->i03_dtbase."'")." 
                               ,".($this->i03_dtlanc == "null" || $this->i03_dtlanc == ""?"null":"'".$this->i03_dtlanc."'")." 
                               ,$this->i03_id_usuario 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Atualização de valores ($this->i03_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Atualização de valores já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Atualização de valores ($this->i03_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->i03_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->i03_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,2413,'$this->i03_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,391,2413,'','".AddSlashes(pg_result($resaco,0,'i03_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,391,2414,'','".AddSlashes(pg_result($resaco,0,'i03_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,391,2415,'','".AddSlashes(pg_result($resaco,0,'i03_numcgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,391,2416,'','".AddSlashes(pg_result($resaco,0,'i03_dtbase'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,391,2418,'','".AddSlashes(pg_result($resaco,0,'i03_dtlanc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,391,2419,'','".AddSlashes(pg_result($resaco,0,'i03_id_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($i03_codigo=null) { 
      $this->atualizacampos();
     $sql = " update infcab set ";
     $virgula = "";
     if(trim($this->i03_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["i03_codigo"])){ 
       $sql  .= $virgula." i03_codigo = $this->i03_codigo ";
       $virgula = ",";
       if(trim($this->i03_codigo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "i03_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->i03_descr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["i03_descr"])){ 
       $sql  .= $virgula." i03_descr = '$this->i03_descr' ";
       $virgula = ",";
       if(trim($this->i03_descr) == null ){ 
         $this->erro_sql = " Campo Descrição nao Informado.";
         $this->erro_campo = "i03_descr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->i03_numcgm)!="" || isset($GLOBALS["HTTP_POST_VARS"]["i03_numcgm"])){ 
       $sql  .= $virgula." i03_numcgm = $this->i03_numcgm ";
       $virgula = ",";
       if(trim($this->i03_numcgm) == null ){ 
         $this->erro_sql = " Campo Número do CGM nao Informado.";
         $this->erro_campo = "i03_numcgm";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->i03_dtbase)!="" || isset($GLOBALS["HTTP_POST_VARS"]["i03_dtbase_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["i03_dtbase_dia"] !="") ){ 
       $sql  .= $virgula." i03_dtbase = '$this->i03_dtbase' ";
       $virgula = ",";
       if(trim($this->i03_dtbase) == null ){ 
         $this->erro_sql = " Campo Data Base nao Informado.";
         $this->erro_campo = "i03_dtbase_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["i03_dtbase_dia"])){ 
         $sql  .= $virgula." i03_dtbase = null ";
         $virgula = ",";
         if(trim($this->i03_dtbase) == null ){ 
           $this->erro_sql = " Campo Data Base nao Informado.";
           $this->erro_campo = "i03_dtbase_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->i03_dtlanc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["i03_dtlanc_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["i03_dtlanc_dia"] !="") ){ 
       $sql  .= $virgula." i03_dtlanc = '$this->i03_dtlanc' ";
       $virgula = ",";
       if(trim($this->i03_dtlanc) == null ){ 
         $this->erro_sql = " Campo Data lançamento nao Informado.";
         $this->erro_campo = "i03_dtlanc_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["i03_dtlanc_dia"])){ 
         $sql  .= $virgula." i03_dtlanc = null ";
         $virgula = ",";
         if(trim($this->i03_dtlanc) == null ){ 
           $this->erro_sql = " Campo Data lançamento nao Informado.";
           $this->erro_campo = "i03_dtlanc_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->i03_id_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["i03_id_usuario"])){ 
       $sql  .= $virgula." i03_id_usuario = $this->i03_id_usuario ";
       $virgula = ",";
       if(trim($this->i03_id_usuario) == null ){ 
         $this->erro_sql = " Campo Login nao Informado.";
         $this->erro_campo = "i03_id_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($i03_codigo!=null){
       $sql .= " i03_codigo = $this->i03_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->i03_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,2413,'$this->i03_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["i03_codigo"]))
           $resac = db_query("insert into db_acount values($acount,391,2413,'".AddSlashes(pg_result($resaco,$conresaco,'i03_codigo'))."','$this->i03_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["i03_descr"]))
           $resac = db_query("insert into db_acount values($acount,391,2414,'".AddSlashes(pg_result($resaco,$conresaco,'i03_descr'))."','$this->i03_descr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["i03_numcgm"]))
           $resac = db_query("insert into db_acount values($acount,391,2415,'".AddSlashes(pg_result($resaco,$conresaco,'i03_numcgm'))."','$this->i03_numcgm',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["i03_dtbase"]))
           $resac = db_query("insert into db_acount values($acount,391,2416,'".AddSlashes(pg_result($resaco,$conresaco,'i03_dtbase'))."','$this->i03_dtbase',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["i03_dtlanc"]))
           $resac = db_query("insert into db_acount values($acount,391,2418,'".AddSlashes(pg_result($resaco,$conresaco,'i03_dtlanc'))."','$this->i03_dtlanc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["i03_id_usuario"]))
           $resac = db_query("insert into db_acount values($acount,391,2419,'".AddSlashes(pg_result($resaco,$conresaco,'i03_id_usuario'))."','$this->i03_id_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Atualização de valores nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->i03_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Atualização de valores nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->i03_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->i03_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($i03_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($i03_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,2413,'$i03_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,391,2413,'','".AddSlashes(pg_result($resaco,$iresaco,'i03_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,391,2414,'','".AddSlashes(pg_result($resaco,$iresaco,'i03_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,391,2415,'','".AddSlashes(pg_result($resaco,$iresaco,'i03_numcgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,391,2416,'','".AddSlashes(pg_result($resaco,$iresaco,'i03_dtbase'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,391,2418,'','".AddSlashes(pg_result($resaco,$iresaco,'i03_dtlanc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,391,2419,'','".AddSlashes(pg_result($resaco,$iresaco,'i03_id_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from infcab
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($i03_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " i03_codigo = $i03_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Atualização de valores nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$i03_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Atualização de valores nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$i03_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$i03_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:infcab";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $i03_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from infcab ";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = infcab.i03_numcgm";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = infcab.i03_id_usuario";
     $sql2 = "";
     if($dbwhere==""){
       if($i03_codigo!=null ){
         $sql2 .= " where infcab.i03_codigo = $i03_codigo "; 
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
   function sql_query_file ( $i03_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from infcab ";
     $sql2 = "";
     if($dbwhere==""){
       if($i03_codigo!=null ){
         $sql2 .= " where infcab.i03_codigo = $i03_codigo "; 
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