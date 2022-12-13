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
//CLASSE DA ENTIDADE cgmfoto
class cl_cgmfoto { 
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
   var $z16_sequencial = 0; 
   var $z16_numcgm = 0; 
   var $z16_id_usuario = 0; 
   var $z16_data_dia = null; 
   var $z16_data_mes = null; 
   var $z16_data_ano = null; 
   var $z16_data = null; 
   var $z16_hora = null; 
   var $z16_fotoativa = 'f'; 
   var $z16_principal = 'f'; 
   var $z16_arquivofoto = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 z16_sequencial = int4 = Código da Foto 
                 z16_numcgm = int4 = Número do Cgm 
                 z16_id_usuario = int4 = Usuário 
                 z16_data = date = Data de Inclusão 
                 z16_hora = char(5) = Hora da Incluisão 
                 z16_fotoativa = bool = Foto Ativa 
                 z16_principal = bool = Foto Principal 
                 z16_arquivofoto = oid = Arquivo da Foto 
                 ";
   //funcao construtor da classe 
   function cl_cgmfoto() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("cgmfoto"); 
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
       $this->z16_sequencial = ($this->z16_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["z16_sequencial"]:$this->z16_sequencial);
       $this->z16_numcgm = ($this->z16_numcgm == ""?@$GLOBALS["HTTP_POST_VARS"]["z16_numcgm"]:$this->z16_numcgm);
       $this->z16_id_usuario = ($this->z16_id_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["z16_id_usuario"]:$this->z16_id_usuario);
       if($this->z16_data == ""){
         $this->z16_data_dia = ($this->z16_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["z16_data_dia"]:$this->z16_data_dia);
         $this->z16_data_mes = ($this->z16_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["z16_data_mes"]:$this->z16_data_mes);
         $this->z16_data_ano = ($this->z16_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["z16_data_ano"]:$this->z16_data_ano);
         if($this->z16_data_dia != ""){
            $this->z16_data = $this->z16_data_ano."-".$this->z16_data_mes."-".$this->z16_data_dia;
         }
       }
       $this->z16_hora = ($this->z16_hora == ""?@$GLOBALS["HTTP_POST_VARS"]["z16_hora"]:$this->z16_hora);
       $this->z16_fotoativa = ($this->z16_fotoativa == "f"?@$GLOBALS["HTTP_POST_VARS"]["z16_fotoativa"]:$this->z16_fotoativa);
       $this->z16_principal = ($this->z16_principal == "f"?@$GLOBALS["HTTP_POST_VARS"]["z16_principal"]:$this->z16_principal);
       $this->z16_arquivofoto = ($this->z16_arquivofoto == ""?@$GLOBALS["HTTP_POST_VARS"]["z16_arquivofoto"]:$this->z16_arquivofoto);
     }else{
       $this->z16_sequencial = ($this->z16_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["z16_sequencial"]:$this->z16_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($z16_sequencial){ 
      $this->atualizacampos();
     if($this->z16_numcgm == null ){ 
       $this->erro_sql = " Campo Número do Cgm nao Informado.";
       $this->erro_campo = "z16_numcgm";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->z16_id_usuario == null ){ 
       $this->erro_sql = " Campo Usuário nao Informado.";
       $this->erro_campo = "z16_id_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->z16_data == null ){ 
       $this->erro_sql = " Campo Data de Inclusão nao Informado.";
       $this->erro_campo = "z16_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->z16_hora == null ){ 
       $this->erro_sql = " Campo Hora da Incluisão nao Informado.";
       $this->erro_campo = "z16_hora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->z16_fotoativa == null ){ 
       $this->z16_fotoativa = "true";
     }
     if($this->z16_principal == null ){ 
       $this->erro_sql = " Campo Foto Principal nao Informado.";
       $this->erro_campo = "z16_principal";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->z16_arquivofoto == null ){ 
       $this->erro_sql = " Campo Arquivo da Foto nao Informado.";
       $this->erro_campo = "z16_arquivofoto";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($z16_sequencial == "" || $z16_sequencial == null ){
       $result = db_query("select nextval('cgmfoto_z16_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: cgmfoto_z16_sequencial_seq do campo: z16_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->z16_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from cgmfoto_z16_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $z16_sequencial)){
         $this->erro_sql = " Campo z16_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->z16_sequencial = $z16_sequencial; 
       }
     }
     if(($this->z16_sequencial == null) || ($this->z16_sequencial == "") ){ 
       $this->erro_sql = " Campo z16_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into cgmfoto(
                                       z16_sequencial 
                                      ,z16_numcgm 
                                      ,z16_id_usuario 
                                      ,z16_data 
                                      ,z16_hora 
                                      ,z16_fotoativa 
                                      ,z16_principal 
                                      ,z16_arquivofoto 
                       )
                values (
                                $this->z16_sequencial 
                               ,$this->z16_numcgm 
                               ,$this->z16_id_usuario 
                               ,".($this->z16_data == "null" || $this->z16_data == ""?"null":"'".$this->z16_data."'")." 
                               ,'$this->z16_hora' 
                               ,'$this->z16_fotoativa' 
                               ,'$this->z16_principal' 
                               ,$this->z16_arquivofoto 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Fotos do Cadastro Geral do Município ($this->z16_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Fotos do Cadastro Geral do Município já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Fotos do Cadastro Geral do Município ($this->z16_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->z16_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->z16_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,17428,'$this->z16_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3082,17428,'','".AddSlashes(pg_result($resaco,0,'z16_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3082,17429,'','".AddSlashes(pg_result($resaco,0,'z16_numcgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3082,17430,'','".AddSlashes(pg_result($resaco,0,'z16_id_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3082,17431,'','".AddSlashes(pg_result($resaco,0,'z16_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3082,17432,'','".AddSlashes(pg_result($resaco,0,'z16_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3082,17433,'','".AddSlashes(pg_result($resaco,0,'z16_fotoativa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3082,17434,'','".AddSlashes(pg_result($resaco,0,'z16_principal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3082,17435,'','".AddSlashes(pg_result($resaco,0,'z16_arquivofoto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($z16_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update cgmfoto set ";
     $virgula = "";
     if(trim($this->z16_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z16_sequencial"])){ 
       $sql  .= $virgula." z16_sequencial = $this->z16_sequencial ";
       $virgula = ",";
       if(trim($this->z16_sequencial) == null ){ 
         $this->erro_sql = " Campo Código da Foto nao Informado.";
         $this->erro_campo = "z16_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->z16_numcgm)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z16_numcgm"])){ 
       $sql  .= $virgula." z16_numcgm = $this->z16_numcgm ";
       $virgula = ",";
       if(trim($this->z16_numcgm) == null ){ 
         $this->erro_sql = " Campo Número do Cgm nao Informado.";
         $this->erro_campo = "z16_numcgm";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->z16_id_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z16_id_usuario"])){ 
       $sql  .= $virgula." z16_id_usuario = $this->z16_id_usuario ";
       $virgula = ",";
       if(trim($this->z16_id_usuario) == null ){ 
         $this->erro_sql = " Campo Usuário nao Informado.";
         $this->erro_campo = "z16_id_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->z16_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z16_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["z16_data_dia"] !="") ){ 
       $sql  .= $virgula." z16_data = '$this->z16_data' ";
       $virgula = ",";
       if(trim($this->z16_data) == null ){ 
         $this->erro_sql = " Campo Data de Inclusão nao Informado.";
         $this->erro_campo = "z16_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["z16_data_dia"])){ 
         $sql  .= $virgula." z16_data = null ";
         $virgula = ",";
         if(trim($this->z16_data) == null ){ 
           $this->erro_sql = " Campo Data de Inclusão nao Informado.";
           $this->erro_campo = "z16_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->z16_hora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z16_hora"])){ 
       $sql  .= $virgula." z16_hora = '$this->z16_hora' ";
       $virgula = ",";
       if(trim($this->z16_hora) == null ){ 
         $this->erro_sql = " Campo Hora da Incluisão nao Informado.";
         $this->erro_campo = "z16_hora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->z16_fotoativa)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z16_fotoativa"])){ 
       $sql  .= $virgula." z16_fotoativa = '$this->z16_fotoativa' ";
       $virgula = ",";
     }
     if(trim($this->z16_principal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z16_principal"])){ 
       $sql  .= $virgula." z16_principal = '$this->z16_principal' ";
       $virgula = ",";
       if(trim($this->z16_principal) == null ){ 
         $this->erro_sql = " Campo Foto Principal nao Informado.";
         $this->erro_campo = "z16_principal";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->z16_arquivofoto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z16_arquivofoto"])){ 
       $sql  .= $virgula." z16_arquivofoto = $this->z16_arquivofoto ";
       $virgula = ",";
       if(trim($this->z16_arquivofoto) == null ){ 
         $this->erro_sql = " Campo Arquivo da Foto nao Informado.";
         $this->erro_campo = "z16_arquivofoto";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($z16_sequencial!=null){
       $sql .= " z16_sequencial = $this->z16_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->z16_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,17428,'$this->z16_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["z16_sequencial"]) || $this->z16_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3082,17428,'".AddSlashes(pg_result($resaco,$conresaco,'z16_sequencial'))."','$this->z16_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["z16_numcgm"]) || $this->z16_numcgm != "")
           $resac = db_query("insert into db_acount values($acount,3082,17429,'".AddSlashes(pg_result($resaco,$conresaco,'z16_numcgm'))."','$this->z16_numcgm',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["z16_id_usuario"]) || $this->z16_id_usuario != "")
           $resac = db_query("insert into db_acount values($acount,3082,17430,'".AddSlashes(pg_result($resaco,$conresaco,'z16_id_usuario'))."','$this->z16_id_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["z16_data"]) || $this->z16_data != "")
           $resac = db_query("insert into db_acount values($acount,3082,17431,'".AddSlashes(pg_result($resaco,$conresaco,'z16_data'))."','$this->z16_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["z16_hora"]) || $this->z16_hora != "")
           $resac = db_query("insert into db_acount values($acount,3082,17432,'".AddSlashes(pg_result($resaco,$conresaco,'z16_hora'))."','$this->z16_hora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["z16_fotoativa"]) || $this->z16_fotoativa != "")
           $resac = db_query("insert into db_acount values($acount,3082,17433,'".AddSlashes(pg_result($resaco,$conresaco,'z16_fotoativa'))."','$this->z16_fotoativa',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["z16_principal"]) || $this->z16_principal != "")
           $resac = db_query("insert into db_acount values($acount,3082,17434,'".AddSlashes(pg_result($resaco,$conresaco,'z16_principal'))."','$this->z16_principal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["z16_arquivofoto"]) || $this->z16_arquivofoto != "")
           $resac = db_query("insert into db_acount values($acount,3082,17435,'".AddSlashes(pg_result($resaco,$conresaco,'z16_arquivofoto'))."','$this->z16_arquivofoto',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Fotos do Cadastro Geral do Município nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->z16_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Fotos do Cadastro Geral do Município nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->z16_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->z16_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($z16_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($z16_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,17428,'$z16_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3082,17428,'','".AddSlashes(pg_result($resaco,$iresaco,'z16_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3082,17429,'','".AddSlashes(pg_result($resaco,$iresaco,'z16_numcgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3082,17430,'','".AddSlashes(pg_result($resaco,$iresaco,'z16_id_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3082,17431,'','".AddSlashes(pg_result($resaco,$iresaco,'z16_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3082,17432,'','".AddSlashes(pg_result($resaco,$iresaco,'z16_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3082,17433,'','".AddSlashes(pg_result($resaco,$iresaco,'z16_fotoativa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3082,17434,'','".AddSlashes(pg_result($resaco,$iresaco,'z16_principal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3082,17435,'','".AddSlashes(pg_result($resaco,$iresaco,'z16_arquivofoto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from cgmfoto
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($z16_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " z16_sequencial = $z16_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Fotos do Cadastro Geral do Município nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$z16_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Fotos do Cadastro Geral do Município nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$z16_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$z16_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:cgmfoto";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $z16_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from cgmfoto ";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = cgmfoto.z16_numcgm";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = cgmfoto.z16_id_usuario";
     $sql2 = "";
     if($dbwhere==""){
       if($z16_sequencial!=null ){
         $sql2 .= " where cgmfoto.z16_sequencial = $z16_sequencial "; 
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
   function sql_query_file ( $z16_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from cgmfoto ";
     $sql2 = "";
     if($dbwhere==""){
       if($z16_sequencial!=null ){
         $sql2 .= " where cgmfoto.z16_sequencial = $z16_sequencial "; 
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