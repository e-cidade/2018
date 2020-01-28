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

//MODULO: protocolo
//CLASSE DA ENTIDADE tipoproc
class cl_tipoproc { 
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
   var $p51_codigo = 0; 
   var $p51_descr = null; 
   var $p51_dtlimite_dia = null; 
   var $p51_dtlimite_mes = null; 
   var $p51_dtlimite_ano = null; 
   var $p51_dtlimite = null; 
   var $p51_instit = 0; 
   var $p51_tipoprocgrupo = 0; 
   var $p51_identificado = 'f'; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 p51_codigo = int4 = Tipo de processo 
                 p51_descr = varchar(60) = Descrição 
                 p51_dtlimite = date = Data Limite 
                 p51_instit = int4 = Cod. Instituição 
                 p51_tipoprocgrupo = int4 = Tipo Processo Grupo 
                 p51_identificado = bool = Identificado 
                 ";
   //funcao construtor da classe 
   function cl_tipoproc() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("tipoproc"); 
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
       $this->p51_codigo = ($this->p51_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["p51_codigo"]:$this->p51_codigo);
       $this->p51_descr = ($this->p51_descr == ""?@$GLOBALS["HTTP_POST_VARS"]["p51_descr"]:$this->p51_descr);
       if($this->p51_dtlimite == ""){
         $this->p51_dtlimite_dia = ($this->p51_dtlimite_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["p51_dtlimite_dia"]:$this->p51_dtlimite_dia);
         $this->p51_dtlimite_mes = ($this->p51_dtlimite_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["p51_dtlimite_mes"]:$this->p51_dtlimite_mes);
         $this->p51_dtlimite_ano = ($this->p51_dtlimite_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["p51_dtlimite_ano"]:$this->p51_dtlimite_ano);
         if($this->p51_dtlimite_dia != ""){
            $this->p51_dtlimite = $this->p51_dtlimite_ano."-".$this->p51_dtlimite_mes."-".$this->p51_dtlimite_dia;
         }
       }
       $this->p51_instit = ($this->p51_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["p51_instit"]:$this->p51_instit);
       $this->p51_tipoprocgrupo = ($this->p51_tipoprocgrupo == ""?@$GLOBALS["HTTP_POST_VARS"]["p51_tipoprocgrupo"]:$this->p51_tipoprocgrupo);
       $this->p51_identificado = ($this->p51_identificado == "f"?@$GLOBALS["HTTP_POST_VARS"]["p51_identificado"]:$this->p51_identificado);
     }else{
       $this->p51_codigo = ($this->p51_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["p51_codigo"]:$this->p51_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($p51_codigo){ 
      $this->atualizacampos();
     if($this->p51_descr == null ){ 
       $this->erro_sql = " Campo Descrição nao Informado.";
       $this->erro_campo = "p51_descr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->p51_dtlimite == null ){ 
       $this->p51_dtlimite = "null";
     }
     if($this->p51_instit == null ){ 
       $this->erro_sql = " Campo Cod. Instituição nao Informado.";
       $this->erro_campo = "p51_instit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->p51_tipoprocgrupo == null ){ 
       $this->erro_sql = " Campo Tipo Processo Grupo nao Informado.";
       $this->erro_campo = "p51_tipoprocgrupo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->p51_identificado == null ){ 
       $this->erro_sql = " Campo Identificado nao Informado.";
       $this->erro_campo = "p51_identificado";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($p51_codigo == "" || $p51_codigo == null ){
       $result = db_query("select nextval('tipoproc_p51_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: tipoproc_p51_codigo_seq do campo: p51_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->p51_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from tipoproc_p51_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $p51_codigo)){
         $this->erro_sql = " Campo p51_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->p51_codigo = $p51_codigo; 
       }
     }
     if(($this->p51_codigo == null) || ($this->p51_codigo == "") ){ 
       $this->erro_sql = " Campo p51_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into tipoproc(
                                       p51_codigo 
                                      ,p51_descr 
                                      ,p51_dtlimite 
                                      ,p51_instit 
                                      ,p51_tipoprocgrupo 
                                      ,p51_identificado 
                       )
                values (
                                $this->p51_codigo 
                               ,'$this->p51_descr' 
                               ,".($this->p51_dtlimite == "null" || $this->p51_dtlimite == ""?"null":"'".$this->p51_dtlimite."'")." 
                               ,$this->p51_instit 
                               ,$this->p51_tipoprocgrupo 
                               ,'$this->p51_identificado' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Tipos de Processo ($this->p51_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Tipos de Processo já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Tipos de Processo ($this->p51_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->p51_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->p51_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,2429,'$this->p51_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,393,2429,'','".AddSlashes(pg_result($resaco,0,'p51_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,393,2430,'','".AddSlashes(pg_result($resaco,0,'p51_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,393,6143,'','".AddSlashes(pg_result($resaco,0,'p51_dtlimite'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,393,10681,'','".AddSlashes(pg_result($resaco,0,'p51_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,393,14732,'','".AddSlashes(pg_result($resaco,0,'p51_tipoprocgrupo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,393,14735,'','".AddSlashes(pg_result($resaco,0,'p51_identificado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($p51_codigo=null) { 
      $this->atualizacampos();
     $sql = " update tipoproc set ";
     $virgula = "";
     if(trim($this->p51_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p51_codigo"])){ 
       $sql  .= $virgula." p51_codigo = $this->p51_codigo ";
       $virgula = ",";
       if(trim($this->p51_codigo) == null ){ 
         $this->erro_sql = " Campo Tipo de processo nao Informado.";
         $this->erro_campo = "p51_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->p51_descr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p51_descr"])){ 
       $sql  .= $virgula." p51_descr = '$this->p51_descr' ";
       $virgula = ",";
       if(trim($this->p51_descr) == null ){ 
         $this->erro_sql = " Campo Descrição nao Informado.";
         $this->erro_campo = "p51_descr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->p51_dtlimite)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p51_dtlimite_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["p51_dtlimite_dia"] !="") ){ 
       $sql  .= $virgula." p51_dtlimite = '$this->p51_dtlimite' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["p51_dtlimite_dia"])){ 
         $sql  .= $virgula." p51_dtlimite = null ";
         $virgula = ",";
       }
     }
     if(trim($this->p51_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p51_instit"])){ 
       $sql  .= $virgula." p51_instit = $this->p51_instit ";
       $virgula = ",";
       if(trim($this->p51_instit) == null ){ 
         $this->erro_sql = " Campo Cod. Instituição nao Informado.";
         $this->erro_campo = "p51_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->p51_tipoprocgrupo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p51_tipoprocgrupo"])){ 
       $sql  .= $virgula." p51_tipoprocgrupo = $this->p51_tipoprocgrupo ";
       $virgula = ",";
       if(trim($this->p51_tipoprocgrupo) == null ){ 
         $this->erro_sql = " Campo Tipo Processo Grupo nao Informado.";
         $this->erro_campo = "p51_tipoprocgrupo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->p51_identificado)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p51_identificado"])){ 
       $sql  .= $virgula." p51_identificado = '$this->p51_identificado' ";
       $virgula = ",";
       if(trim($this->p51_identificado) == null ){ 
         $this->erro_sql = " Campo Identificado nao Informado.";
         $this->erro_campo = "p51_identificado";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($p51_codigo!=null){
       $sql .= " p51_codigo = $this->p51_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->p51_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,2429,'$this->p51_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["p51_codigo"]) || $this->p51_codigo != "")
           $resac = db_query("insert into db_acount values($acount,393,2429,'".AddSlashes(pg_result($resaco,$conresaco,'p51_codigo'))."','$this->p51_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["p51_descr"]) || $this->p51_descr != "")
           $resac = db_query("insert into db_acount values($acount,393,2430,'".AddSlashes(pg_result($resaco,$conresaco,'p51_descr'))."','$this->p51_descr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["p51_dtlimite"]) || $this->p51_dtlimite != "")
           $resac = db_query("insert into db_acount values($acount,393,6143,'".AddSlashes(pg_result($resaco,$conresaco,'p51_dtlimite'))."','$this->p51_dtlimite',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["p51_instit"]) || $this->p51_instit != "")
           $resac = db_query("insert into db_acount values($acount,393,10681,'".AddSlashes(pg_result($resaco,$conresaco,'p51_instit'))."','$this->p51_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["p51_tipoprocgrupo"]) || $this->p51_tipoprocgrupo != "")
           $resac = db_query("insert into db_acount values($acount,393,14732,'".AddSlashes(pg_result($resaco,$conresaco,'p51_tipoprocgrupo'))."','$this->p51_tipoprocgrupo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["p51_identificado"]) || $this->p51_identificado != "")
           $resac = db_query("insert into db_acount values($acount,393,14735,'".AddSlashes(pg_result($resaco,$conresaco,'p51_identificado'))."','$this->p51_identificado',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Tipos de Processo nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->p51_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Tipos de Processo nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->p51_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->p51_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($p51_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($p51_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,2429,'$p51_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,393,2429,'','".AddSlashes(pg_result($resaco,$iresaco,'p51_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,393,2430,'','".AddSlashes(pg_result($resaco,$iresaco,'p51_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,393,6143,'','".AddSlashes(pg_result($resaco,$iresaco,'p51_dtlimite'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,393,10681,'','".AddSlashes(pg_result($resaco,$iresaco,'p51_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,393,14732,'','".AddSlashes(pg_result($resaco,$iresaco,'p51_tipoprocgrupo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,393,14735,'','".AddSlashes(pg_result($resaco,$iresaco,'p51_identificado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from tipoproc
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($p51_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " p51_codigo = $p51_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Tipos de Processo nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$p51_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Tipos de Processo nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$p51_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$p51_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:tipoproc";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $p51_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from tipoproc ";
     $sql .= "      inner join db_config     on db_config.codigo             = tipoproc.p51_instit";
     $sql .= "      inner join tipoprocgrupo on tipoprocgrupo.p40_sequencial = tipoproc.p51_tipoprocgrupo";
     $sql .= "      inner join cgm           on cgm.z01_numcgm               = db_config.numcgm";
     
     $sql2 = "";
     if($dbwhere==""){
       if($p51_codigo!=null ){
         $sql2 .= " where tipoproc.p51_codigo = $p51_codigo "; 
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
   function sql_query_file ( $p51_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from tipoproc ";
     $sql2 = "";
     if($dbwhere==""){
       if($p51_codigo!=null ){
         $sql2 .= " where tipoproc.p51_codigo = $p51_codigo "; 
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
   function sql_query_depto( $p51_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from tipoproc ";
     $sql .= "      inner join db_config     on db_config.codigo             = tipoproc.p51_instit";
     $sql .= "      inner join tipoprocgrupo on tipoprocgrupo.p40_sequencial = tipoproc.p51_tipoprocgrupo";
     $sql .= "      inner join cgm           on cgm.z01_numcgm               = db_config.numcgm";
     $sql .= "      left  join tipoprocdepto on tipoprocdepto.p41_tipoproc   = tipoproc.p51_codigo";
     
     $sql2 = "";
     if($dbwhere==""){
       if($p51_codigo!=null ){
         $sql2 .= " where tipoproc.p51_codigo = $p51_codigo "; 
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