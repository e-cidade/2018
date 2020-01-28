<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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
//CLASSE DA ENTIDADE rhlotavinc
class cl_rhlotavinc { 
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
   var $rh25_codlotavinc = 0; 
   var $rh25_codigo = 0; 
   var $rh25_vinculo = null; 
   var $rh25_anousu = 0; 
   var $rh25_projativ = 0; 
   var $rh25_recurso = 0; 
   var $rh25_programa = 0; 
   var $rh25_subfuncao = 0; 
   var $rh25_funcao = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 rh25_codlotavinc = int8 = Código 
                 rh25_codigo = int4 = Código da Lotação 
                 rh25_vinculo = varchar(1) = Vínculo 
                 rh25_anousu = int4 = Exercício 
                 rh25_projativ = int4 = Projetos / Atividades 
                 rh25_recurso = int4 = Recurso 
                 rh25_programa = int4 = Programa 
                 rh25_subfuncao = int4 = Subfunção 
                 rh25_funcao = int4 = Função 
                 ";
   //funcao construtor da classe 
   function cl_rhlotavinc() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("rhlotavinc"); 
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
       $this->rh25_codlotavinc = ($this->rh25_codlotavinc == ""?@$GLOBALS["HTTP_POST_VARS"]["rh25_codlotavinc"]:$this->rh25_codlotavinc);
       $this->rh25_codigo = ($this->rh25_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["rh25_codigo"]:$this->rh25_codigo);
       $this->rh25_vinculo = ($this->rh25_vinculo == ""?@$GLOBALS["HTTP_POST_VARS"]["rh25_vinculo"]:$this->rh25_vinculo);
       $this->rh25_anousu = ($this->rh25_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["rh25_anousu"]:$this->rh25_anousu);
       $this->rh25_projativ = ($this->rh25_projativ == ""?@$GLOBALS["HTTP_POST_VARS"]["rh25_projativ"]:$this->rh25_projativ);
       $this->rh25_recurso = ($this->rh25_recurso == ""?@$GLOBALS["HTTP_POST_VARS"]["rh25_recurso"]:$this->rh25_recurso);
       $this->rh25_programa = ($this->rh25_programa == ""?@$GLOBALS["HTTP_POST_VARS"]["rh25_programa"]:$this->rh25_programa);
       $this->rh25_subfuncao = ($this->rh25_subfuncao == ""?@$GLOBALS["HTTP_POST_VARS"]["rh25_subfuncao"]:$this->rh25_subfuncao);
       $this->rh25_funcao = ($this->rh25_funcao == ""?@$GLOBALS["HTTP_POST_VARS"]["rh25_funcao"]:$this->rh25_funcao);
     }else{
       $this->rh25_codlotavinc = ($this->rh25_codlotavinc == ""?@$GLOBALS["HTTP_POST_VARS"]["rh25_codlotavinc"]:$this->rh25_codlotavinc);
     }
   }
   // funcao para inclusao
   function incluir ($rh25_codlotavinc){ 
      $this->atualizacampos();
     if($this->rh25_codigo == null ){ 
       $this->erro_sql = " Campo Código da Lotação nao Informado.";
       $this->erro_campo = "rh25_codigo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh25_vinculo == null ){ 
       $this->erro_sql = " Campo Vínculo nao Informado.";
       $this->erro_campo = "rh25_vinculo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh25_anousu == null ){ 
       $this->erro_sql = " Campo Exercício nao Informado.";
       $this->erro_campo = "rh25_anousu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh25_projativ == null ){ 
       $this->erro_sql = " Campo Projetos / Atividades nao Informado.";
       $this->erro_campo = "rh25_projativ";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh25_recurso == null ){ 
       $this->erro_sql = " Campo Recurso nao Informado.";
       $this->erro_campo = "rh25_recurso";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh25_programa == null ){
       $this->rh25_programa = "null";
     }
     if($this->rh25_subfuncao == null ){
       $this->rh25_subfuncao = "null";
     }
     if($this->rh25_funcao == null ){
       $this->rh25_funcao = "null";
     }
     if($rh25_codlotavinc == "" || $rh25_codlotavinc == null ){
       $result = db_query("select nextval('rhlotavinc_codlotav_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: rhlotavinc_codlotav_seq do campo: rh25_codlotavinc"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->rh25_codlotavinc = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from rhlotavinc_codlotav_seq");
       if(($result != false) && (pg_result($result,0,0) < $rh25_codlotavinc)){
         $this->erro_sql = " Campo rh25_codlotavinc maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->rh25_codlotavinc = $rh25_codlotavinc; 
       }
     }
     if(($this->rh25_codlotavinc == null) || ($this->rh25_codlotavinc == "") ){ 
       $this->erro_sql = " Campo rh25_codlotavinc nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into rhlotavinc(
                                       rh25_codlotavinc 
                                      ,rh25_codigo 
                                      ,rh25_vinculo 
                                      ,rh25_anousu 
                                      ,rh25_projativ 
                                      ,rh25_recurso 
                                      ,rh25_programa 
                                      ,rh25_subfuncao 
                                      ,rh25_funcao 
                       )
                values (
                                $this->rh25_codlotavinc 
                               ,$this->rh25_codigo 
                               ,'$this->rh25_vinculo' 
                               ,$this->rh25_anousu 
                               ,$this->rh25_projativ 
                               ,$this->rh25_recurso 
                               ,$this->rh25_programa 
                               ,$this->rh25_subfuncao 
                               ,$this->rh25_funcao 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Vínculo entre lotação/vínculo/atividade ($this->rh25_codlotavinc) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Vínculo entre lotação/vínculo/atividade já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Vínculo entre lotação/vínculo/atividade ($this->rh25_codlotavinc) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh25_codlotavinc;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->rh25_codlotavinc));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,7134,'$this->rh25_codlotavinc','I')");
       $resac = db_query("insert into db_acount values($acount,1182,7134,'','".AddSlashes(pg_result($resaco,0,'rh25_codlotavinc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1182,7135,'','".AddSlashes(pg_result($resaco,0,'rh25_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1182,7136,'','".AddSlashes(pg_result($resaco,0,'rh25_vinculo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1182,7137,'','".AddSlashes(pg_result($resaco,0,'rh25_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1182,7138,'','".AddSlashes(pg_result($resaco,0,'rh25_projativ'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1182,7238,'','".AddSlashes(pg_result($resaco,0,'rh25_recurso'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1182,19146,'','".AddSlashes(pg_result($resaco,0,'rh25_programa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1182,19147,'','".AddSlashes(pg_result($resaco,0,'rh25_subfuncao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1182,19148,'','".AddSlashes(pg_result($resaco,0,'rh25_funcao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($rh25_codlotavinc=null) { 
      $this->atualizacampos();
     $sql = " update rhlotavinc set ";
     $virgula = "";
     if(trim($this->rh25_codlotavinc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh25_codlotavinc"])){ 
       $sql  .= $virgula." rh25_codlotavinc = $this->rh25_codlotavinc ";
       $virgula = ",";
       if(trim($this->rh25_codlotavinc) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "rh25_codlotavinc";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh25_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh25_codigo"])){ 
       $sql  .= $virgula." rh25_codigo = $this->rh25_codigo ";
       $virgula = ",";
       if(trim($this->rh25_codigo) == null ){ 
         $this->erro_sql = " Campo Código da Lotação nao Informado.";
         $this->erro_campo = "rh25_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh25_vinculo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh25_vinculo"])){ 
       $sql  .= $virgula." rh25_vinculo = '$this->rh25_vinculo' ";
       $virgula = ",";
       if(trim($this->rh25_vinculo) == null ){ 
         $this->erro_sql = " Campo Vínculo nao Informado.";
         $this->erro_campo = "rh25_vinculo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh25_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh25_anousu"])){ 
       $sql  .= $virgula." rh25_anousu = $this->rh25_anousu ";
       $virgula = ",";
       if(trim($this->rh25_anousu) == null ){ 
         $this->erro_sql = " Campo Exercício nao Informado.";
         $this->erro_campo = "rh25_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh25_projativ)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh25_projativ"])){ 
       $sql  .= $virgula." rh25_projativ = $this->rh25_projativ ";
       $virgula = ",";
       if(trim($this->rh25_projativ) == null ){ 
         $this->erro_sql = " Campo Projetos / Atividades nao Informado.";
         $this->erro_campo = "rh25_projativ";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh25_recurso)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh25_recurso"])){ 
       $sql  .= $virgula." rh25_recurso = $this->rh25_recurso ";
       $virgula = ",";
       if(trim($this->rh25_recurso) == null ){ 
         $this->erro_sql = " Campo Recurso nao Informado.";
         $this->erro_campo = "rh25_recurso";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh25_programa)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh25_programa"])){ 
        if(trim($this->rh25_programa)=="" && isset($GLOBALS["HTTP_POST_VARS"]["rh25_programa"])){ 
           $this->rh25_programa = "null" ; 
        } 
       $sql  .= $virgula." rh25_programa = $this->rh25_programa ";
       $virgula = ",";
     }
     if(trim($this->rh25_subfuncao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh25_subfuncao"])){ 
        if(trim($this->rh25_subfuncao)=="" && isset($GLOBALS["HTTP_POST_VARS"]["rh25_subfuncao"])){ 
           $this->rh25_subfuncao = "null" ; 
        } 
       $sql  .= $virgula." rh25_subfuncao = $this->rh25_subfuncao ";
       $virgula = ",";
     }
     if(trim($this->rh25_funcao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh25_funcao"])){ 
        if(trim($this->rh25_funcao)=="" && isset($GLOBALS["HTTP_POST_VARS"]["rh25_funcao"])){ 
           $this->rh25_funcao = "null" ; 
        } 
       $sql  .= $virgula." rh25_funcao = $this->rh25_funcao ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($rh25_codlotavinc!=null){
       $sql .= " rh25_codlotavinc = $this->rh25_codlotavinc";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->rh25_codlotavinc));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,7134,'$this->rh25_codlotavinc','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh25_codlotavinc"]) || $this->rh25_codlotavinc != "")
           $resac = db_query("insert into db_acount values($acount,1182,7134,'".AddSlashes(pg_result($resaco,$conresaco,'rh25_codlotavinc'))."','$this->rh25_codlotavinc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh25_codigo"]) || $this->rh25_codigo != "")
           $resac = db_query("insert into db_acount values($acount,1182,7135,'".AddSlashes(pg_result($resaco,$conresaco,'rh25_codigo'))."','$this->rh25_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh25_vinculo"]) || $this->rh25_vinculo != "")
           $resac = db_query("insert into db_acount values($acount,1182,7136,'".AddSlashes(pg_result($resaco,$conresaco,'rh25_vinculo'))."','$this->rh25_vinculo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh25_anousu"]) || $this->rh25_anousu != "")
           $resac = db_query("insert into db_acount values($acount,1182,7137,'".AddSlashes(pg_result($resaco,$conresaco,'rh25_anousu'))."','$this->rh25_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh25_projativ"]) || $this->rh25_projativ != "")
           $resac = db_query("insert into db_acount values($acount,1182,7138,'".AddSlashes(pg_result($resaco,$conresaco,'rh25_projativ'))."','$this->rh25_projativ',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh25_recurso"]) || $this->rh25_recurso != "")
           $resac = db_query("insert into db_acount values($acount,1182,7238,'".AddSlashes(pg_result($resaco,$conresaco,'rh25_recurso'))."','$this->rh25_recurso',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh25_programa"]) || $this->rh25_programa != "")
           $resac = db_query("insert into db_acount values($acount,1182,19146,'".AddSlashes(pg_result($resaco,$conresaco,'rh25_programa'))."','$this->rh25_programa',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh25_subfuncao"]) || $this->rh25_subfuncao != "")
           $resac = db_query("insert into db_acount values($acount,1182,19147,'".AddSlashes(pg_result($resaco,$conresaco,'rh25_subfuncao'))."','$this->rh25_subfuncao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh25_funcao"]) || $this->rh25_funcao != "")
           $resac = db_query("insert into db_acount values($acount,1182,19148,'".AddSlashes(pg_result($resaco,$conresaco,'rh25_funcao'))."','$this->rh25_funcao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Vínculo entre lotação/vínculo/atividade nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh25_codlotavinc;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Vínculo entre lotação/vínculo/atividade nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh25_codlotavinc;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh25_codlotavinc;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($rh25_codlotavinc=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($rh25_codlotavinc));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,7134,'$rh25_codlotavinc','E')");
         $resac = db_query("insert into db_acount values($acount,1182,7134,'','".AddSlashes(pg_result($resaco,$iresaco,'rh25_codlotavinc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1182,7135,'','".AddSlashes(pg_result($resaco,$iresaco,'rh25_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1182,7136,'','".AddSlashes(pg_result($resaco,$iresaco,'rh25_vinculo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1182,7137,'','".AddSlashes(pg_result($resaco,$iresaco,'rh25_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1182,7138,'','".AddSlashes(pg_result($resaco,$iresaco,'rh25_projativ'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1182,7238,'','".AddSlashes(pg_result($resaco,$iresaco,'rh25_recurso'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1182,19146,'','".AddSlashes(pg_result($resaco,$iresaco,'rh25_programa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1182,19147,'','".AddSlashes(pg_result($resaco,$iresaco,'rh25_subfuncao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1182,19148,'','".AddSlashes(pg_result($resaco,$iresaco,'rh25_funcao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from rhlotavinc
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($rh25_codlotavinc != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " rh25_codlotavinc = $rh25_codlotavinc ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Vínculo entre lotação/vínculo/atividade nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$rh25_codlotavinc;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Vínculo entre lotação/vínculo/atividade nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$rh25_codlotavinc;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$rh25_codlotavinc;
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
        $this->erro_sql   = "Record Vazio na Tabela:rhlotavinc";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $rh25_codlotavinc=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhlotavinc ";
     $sql .= "      inner join orctiporec  on  orctiporec.o15_codigo = rhlotavinc.rh25_recurso";
     $sql .= "      left  join orcfuncao  on  orcfuncao.o52_funcao = rhlotavinc.rh25_funcao";
     $sql .= "      left  join orcsubfuncao  on  orcsubfuncao.o53_subfuncao = rhlotavinc.rh25_subfuncao";
     $sql .= "      left  join orcprograma  on  orcprograma.o54_anousu = rhlotavinc.rh25_anousu and  orcprograma.o54_programa = rhlotavinc.rh25_programa";
     $sql .= "      inner join orcprojativ  on  orcprojativ.o55_anousu = rhlotavinc.rh25_anousu and  orcprojativ.o55_projativ = rhlotavinc.rh25_projativ";
     $sql .= "      inner join rhlota  on  rhlota.r70_codigo = rhlotavinc.rh25_codigo";
     $sql .= "      inner join db_estruturavalor  on  db_estruturavalor.db121_sequencial = orctiporec.o15_db_estruturavalor";
     $sql .= "      inner join db_config  on  db_config.codigo = orcprojativ.o55_instit";
     $sql .= "      inner join orcproduto  on  orcproduto.o22_codproduto = orcprojativ.o55_orcproduto";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = rhlota.r70_numcgm";
     $sql .= "      inner join db_estrutura  as a on   a.db77_codestrut = rhlota.r70_codestrut";
     $sql .= "      inner join concarpeculiar  on  concarpeculiar.c58_sequencial = rhlota.r70_concarpeculiar";
     $sql2 = "";
     if($dbwhere==""){
       if($rh25_codlotavinc!=null ){
         $sql2 .= " where rhlotavinc.rh25_codlotavinc = $rh25_codlotavinc "; 
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
   function sql_query_file ( $rh25_codlotavinc=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhlotavinc ";
     $sql2 = "";
     if($dbwhere==""){
       if($rh25_codlotavinc!=null ){
         $sql2 .= " where rhlotavinc.rh25_codlotavinc = $rh25_codlotavinc "; 
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

  function sql_query_servidores($iAnoFolha, $iMesFolha, $iCodigoRecurso, $sCampos, $iInstituicao = null) {

  	if ( empty($sCampos) ) {
  		$sCampos = "*";
  	}
  	 
  	if (empty($iInstituicao)) {
  		$iInstituicao = db_getsession('DB_instit');
  	}
  	 
  	$sSql = "select {$sCampos}                                                         \n";
  	$sSql.= "  from rhpessoalmov                                                       \n";
  	$sSql.= "       inner join rhlota       on r70_codigo  = rh02_lota                 \n";
  	$sSql.= "                              and r70_instit  = rh02_instit               \n";
  	$sSql.= "       inner join rhlotavinc   on rh25_codigo = r70_codigo                \n";
  	$sSql.= "                              and rh25_anousu = rhpessoalmov.rh02_anousu  \n";
  	$sSql.= "       inner join orctiporec   on o15_codigo  = rh25_recurso              \n";
  	$sSql.= " where rh02_anousu = $iAnoFolha                                           \n";
  	$sSql.= "   and rh02_mesusu = $iMesFolha                                           \n";
  	$sSql.= "   and rh02_instit = $iInstituicao                                        \n";
  	$sSql.= "   and o15_codigo  = $iCodigoRecurso;                                     \n";
  	 
  	return $sSql;

  }
}
?>