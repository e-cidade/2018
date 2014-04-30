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
//CLASSE DA ENTIDADE rhsefip
class cl_rhsefip { 
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
   var $rh90_sequencial = 0; 
   var $rh90_anousu = 0; 
   var $rh90_mesusu = 0; 
   var $rh90_arquivo = 0; 
   var $rh90_id_usuario = 0; 
   var $rh90_datagera_dia = null; 
   var $rh90_datagera_mes = null; 
   var $rh90_datagera_ano = null; 
   var $rh90_datagera = null; 
   var $rh90_horagera = null; 
   var $rh90_valorcomp = 0; 
   var $rh90_compini = null; 
   var $rh90_compfim = null; 
   var $rh90_ativa = 'f'; 
   var $rh90_instit = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 rh90_sequencial = int4 = Sequencial 
                 rh90_anousu = int4 = Exercício 
                 rh90_mesusu = int4 = Mês 
                 rh90_arquivo = oid = Arquivo SEFIP 
                 rh90_id_usuario = int4 = Usuário 
                 rh90_datagera = date = Data Geração 
                 rh90_horagera = char(5) = Hora Geração 
                 rh90_valorcomp = float4 = Valor Compensação 
                 rh90_compini = char(7) = Competência Inicial da Compensação 
                 rh90_compfim = char(7) = Competência Final da Compensação 
                 rh90_ativa = bool = Ativa 
                 rh90_instit = int4 = Instituição 
                 ";
   //funcao construtor da classe 
   function cl_rhsefip() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("rhsefip"); 
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
       $this->rh90_sequencial = ($this->rh90_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh90_sequencial"]:$this->rh90_sequencial);
       $this->rh90_anousu = ($this->rh90_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["rh90_anousu"]:$this->rh90_anousu);
       $this->rh90_mesusu = ($this->rh90_mesusu == ""?@$GLOBALS["HTTP_POST_VARS"]["rh90_mesusu"]:$this->rh90_mesusu);
       $this->rh90_arquivo = ($this->rh90_arquivo == ""?@$GLOBALS["HTTP_POST_VARS"]["rh90_arquivo"]:$this->rh90_arquivo);
       $this->rh90_id_usuario = ($this->rh90_id_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["rh90_id_usuario"]:$this->rh90_id_usuario);
       if($this->rh90_datagera == ""){
         $this->rh90_datagera_dia = ($this->rh90_datagera_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["rh90_datagera_dia"]:$this->rh90_datagera_dia);
         $this->rh90_datagera_mes = ($this->rh90_datagera_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["rh90_datagera_mes"]:$this->rh90_datagera_mes);
         $this->rh90_datagera_ano = ($this->rh90_datagera_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["rh90_datagera_ano"]:$this->rh90_datagera_ano);
         if($this->rh90_datagera_dia != ""){
            $this->rh90_datagera = $this->rh90_datagera_ano."-".$this->rh90_datagera_mes."-".$this->rh90_datagera_dia;
         }
       }
       $this->rh90_horagera = ($this->rh90_horagera == ""?@$GLOBALS["HTTP_POST_VARS"]["rh90_horagera"]:$this->rh90_horagera);
       $this->rh90_valorcomp = ($this->rh90_valorcomp == ""?@$GLOBALS["HTTP_POST_VARS"]["rh90_valorcomp"]:$this->rh90_valorcomp);
       $this->rh90_compini = ($this->rh90_compini == ""?@$GLOBALS["HTTP_POST_VARS"]["rh90_compini"]:$this->rh90_compini);
       $this->rh90_compfim = ($this->rh90_compfim == ""?@$GLOBALS["HTTP_POST_VARS"]["rh90_compfim"]:$this->rh90_compfim);
       $this->rh90_ativa = ($this->rh90_ativa == "f"?@$GLOBALS["HTTP_POST_VARS"]["rh90_ativa"]:$this->rh90_ativa);
       $this->rh90_instit = ($this->rh90_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["rh90_instit"]:$this->rh90_instit);
     }else{
       $this->rh90_sequencial = ($this->rh90_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh90_sequencial"]:$this->rh90_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($rh90_sequencial){ 
      $this->atualizacampos();
     if($this->rh90_anousu == null ){ 
       $this->erro_sql = " Campo Exercício nao Informado.";
       $this->erro_campo = "rh90_anousu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh90_mesusu == null ){ 
       $this->erro_sql = " Campo Mês nao Informado.";
       $this->erro_campo = "rh90_mesusu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh90_arquivo == null ){ 
       $this->rh90_arquivo = "null";
     }
     if($this->rh90_id_usuario == null ){ 
       $this->erro_sql = " Campo Usuário nao Informado.";
       $this->erro_campo = "rh90_id_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh90_datagera == null ){ 
       $this->erro_sql = " Campo Data Geração nao Informado.";
       $this->erro_campo = "rh90_datagera_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh90_horagera == null ){ 
       $this->erro_sql = " Campo Hora Geração nao Informado.";
       $this->erro_campo = "rh90_horagera";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh90_valorcomp == null ){ 
       $this->rh90_valorcomp = "0";
     }
     if($this->rh90_ativa == null ){ 
       $this->erro_sql = " Campo Ativa nao Informado.";
       $this->erro_campo = "rh90_ativa";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh90_instit == null ){ 
       $this->erro_sql = " Campo Instituição nao Informado.";
       $this->erro_campo = "rh90_instit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($rh90_sequencial == "" || $rh90_sequencial == null ){
       $result = db_query("select nextval('rhsefip_rh90_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: rhsefip_rh90_sequencial_seq do campo: rh90_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->rh90_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from rhsefip_rh90_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $rh90_sequencial)){
         $this->erro_sql = " Campo rh90_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->rh90_sequencial = $rh90_sequencial; 
       }
     }
     if(($this->rh90_sequencial == null) || ($this->rh90_sequencial == "") ){ 
       $this->erro_sql = " Campo rh90_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into rhsefip(
                                       rh90_sequencial 
                                      ,rh90_anousu 
                                      ,rh90_mesusu 
                                      ,rh90_arquivo 
                                      ,rh90_id_usuario 
                                      ,rh90_datagera 
                                      ,rh90_horagera 
                                      ,rh90_valorcomp 
                                      ,rh90_compini 
                                      ,rh90_compfim 
                                      ,rh90_ativa 
                                      ,rh90_instit 
                       )
                values (
                                $this->rh90_sequencial 
                               ,$this->rh90_anousu 
                               ,$this->rh90_mesusu 
                               ,$this->rh90_arquivo 
                               ,$this->rh90_id_usuario 
                               ,".($this->rh90_datagera == "null" || $this->rh90_datagera == ""?"null":"'".$this->rh90_datagera."'")." 
                               ,'$this->rh90_horagera' 
                               ,$this->rh90_valorcomp 
                               ,'$this->rh90_compini' 
                               ,'$this->rh90_compfim' 
                               ,'$this->rh90_ativa' 
                               ,$this->rh90_instit 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Geração da SEFIP ($this->rh90_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Geração da SEFIP já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Geração da SEFIP ($this->rh90_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh90_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->rh90_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,17533,'$this->rh90_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3097,17533,'','".AddSlashes(pg_result($resaco,0,'rh90_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3097,17538,'','".AddSlashes(pg_result($resaco,0,'rh90_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3097,17539,'','".AddSlashes(pg_result($resaco,0,'rh90_mesusu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3097,17537,'','".AddSlashes(pg_result($resaco,0,'rh90_arquivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3097,17535,'','".AddSlashes(pg_result($resaco,0,'rh90_id_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3097,17534,'','".AddSlashes(pg_result($resaco,0,'rh90_datagera'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3097,17536,'','".AddSlashes(pg_result($resaco,0,'rh90_horagera'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3097,17540,'','".AddSlashes(pg_result($resaco,0,'rh90_valorcomp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3097,17541,'','".AddSlashes(pg_result($resaco,0,'rh90_compini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3097,17542,'','".AddSlashes(pg_result($resaco,0,'rh90_compfim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3097,17543,'','".AddSlashes(pg_result($resaco,0,'rh90_ativa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3097,17544,'','".AddSlashes(pg_result($resaco,0,'rh90_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($rh90_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update rhsefip set ";
     $virgula = "";
     if(trim($this->rh90_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh90_sequencial"])){ 
       $sql  .= $virgula." rh90_sequencial = $this->rh90_sequencial ";
       $virgula = ",";
       if(trim($this->rh90_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "rh90_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh90_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh90_anousu"])){ 
       $sql  .= $virgula." rh90_anousu = $this->rh90_anousu ";
       $virgula = ",";
       if(trim($this->rh90_anousu) == null ){ 
         $this->erro_sql = " Campo Exercício nao Informado.";
         $this->erro_campo = "rh90_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh90_mesusu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh90_mesusu"])){ 
       $sql  .= $virgula." rh90_mesusu = $this->rh90_mesusu ";
       $virgula = ",";
       if(trim($this->rh90_mesusu) == null ){ 
         $this->erro_sql = " Campo Mês nao Informado.";
         $this->erro_campo = "rh90_mesusu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh90_arquivo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh90_arquivo"])){ 
       $sql  .= $virgula." rh90_arquivo = $this->rh90_arquivo ";
       $virgula = ",";
     }
     if(trim($this->rh90_id_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh90_id_usuario"])){ 
       $sql  .= $virgula." rh90_id_usuario = $this->rh90_id_usuario ";
       $virgula = ",";
       if(trim($this->rh90_id_usuario) == null ){ 
         $this->erro_sql = " Campo Usuário nao Informado.";
         $this->erro_campo = "rh90_id_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh90_datagera)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh90_datagera_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["rh90_datagera_dia"] !="") ){ 
       $sql  .= $virgula." rh90_datagera = '$this->rh90_datagera' ";
       $virgula = ",";
       if(trim($this->rh90_datagera) == null ){ 
         $this->erro_sql = " Campo Data Geração nao Informado.";
         $this->erro_campo = "rh90_datagera_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["rh90_datagera_dia"])){ 
         $sql  .= $virgula." rh90_datagera = null ";
         $virgula = ",";
         if(trim($this->rh90_datagera) == null ){ 
           $this->erro_sql = " Campo Data Geração nao Informado.";
           $this->erro_campo = "rh90_datagera_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->rh90_horagera)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh90_horagera"])){ 
       $sql  .= $virgula." rh90_horagera = '$this->rh90_horagera' ";
       $virgula = ",";
       if(trim($this->rh90_horagera) == null ){ 
         $this->erro_sql = " Campo Hora Geração nao Informado.";
         $this->erro_campo = "rh90_horagera";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh90_valorcomp)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh90_valorcomp"])){ 
        if(trim($this->rh90_valorcomp)=="" && isset($GLOBALS["HTTP_POST_VARS"]["rh90_valorcomp"])){ 
           $this->rh90_valorcomp = "0" ; 
        } 
       $sql  .= $virgula." rh90_valorcomp = $this->rh90_valorcomp ";
       $virgula = ",";
     }
     if(trim($this->rh90_compini)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh90_compini"])){ 
       $sql  .= $virgula." rh90_compini = '$this->rh90_compini' ";
       $virgula = ",";
     }
     if(trim($this->rh90_compfim)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh90_compfim"])){ 
       $sql  .= $virgula." rh90_compfim = '$this->rh90_compfim' ";
       $virgula = ",";
     }
     if(trim($this->rh90_ativa)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh90_ativa"])){ 
       $sql  .= $virgula." rh90_ativa = '$this->rh90_ativa' ";
       $virgula = ",";
       if(trim($this->rh90_ativa) == null ){ 
         $this->erro_sql = " Campo Ativa nao Informado.";
         $this->erro_campo = "rh90_ativa";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh90_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh90_instit"])){ 
       $sql  .= $virgula." rh90_instit = $this->rh90_instit ";
       $virgula = ",";
       if(trim($this->rh90_instit) == null ){ 
         $this->erro_sql = " Campo Instituição nao Informado.";
         $this->erro_campo = "rh90_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($rh90_sequencial!=null){
       $sql .= " rh90_sequencial = $this->rh90_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->rh90_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,17533,'$this->rh90_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh90_sequencial"]) || $this->rh90_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3097,17533,'".AddSlashes(pg_result($resaco,$conresaco,'rh90_sequencial'))."','$this->rh90_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh90_anousu"]) || $this->rh90_anousu != "")
           $resac = db_query("insert into db_acount values($acount,3097,17538,'".AddSlashes(pg_result($resaco,$conresaco,'rh90_anousu'))."','$this->rh90_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh90_mesusu"]) || $this->rh90_mesusu != "")
           $resac = db_query("insert into db_acount values($acount,3097,17539,'".AddSlashes(pg_result($resaco,$conresaco,'rh90_mesusu'))."','$this->rh90_mesusu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh90_arquivo"]) || $this->rh90_arquivo != "")
           $resac = db_query("insert into db_acount values($acount,3097,17537,'".AddSlashes(pg_result($resaco,$conresaco,'rh90_arquivo'))."','$this->rh90_arquivo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh90_id_usuario"]) || $this->rh90_id_usuario != "")
           $resac = db_query("insert into db_acount values($acount,3097,17535,'".AddSlashes(pg_result($resaco,$conresaco,'rh90_id_usuario'))."','$this->rh90_id_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh90_datagera"]) || $this->rh90_datagera != "")
           $resac = db_query("insert into db_acount values($acount,3097,17534,'".AddSlashes(pg_result($resaco,$conresaco,'rh90_datagera'))."','$this->rh90_datagera',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh90_horagera"]) || $this->rh90_horagera != "")
           $resac = db_query("insert into db_acount values($acount,3097,17536,'".AddSlashes(pg_result($resaco,$conresaco,'rh90_horagera'))."','$this->rh90_horagera',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh90_valorcomp"]) || $this->rh90_valorcomp != "")
           $resac = db_query("insert into db_acount values($acount,3097,17540,'".AddSlashes(pg_result($resaco,$conresaco,'rh90_valorcomp'))."','$this->rh90_valorcomp',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh90_compini"]) || $this->rh90_compini != "")
           $resac = db_query("insert into db_acount values($acount,3097,17541,'".AddSlashes(pg_result($resaco,$conresaco,'rh90_compini'))."','$this->rh90_compini',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh90_compfim"]) || $this->rh90_compfim != "")
           $resac = db_query("insert into db_acount values($acount,3097,17542,'".AddSlashes(pg_result($resaco,$conresaco,'rh90_compfim'))."','$this->rh90_compfim',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh90_ativa"]) || $this->rh90_ativa != "")
           $resac = db_query("insert into db_acount values($acount,3097,17543,'".AddSlashes(pg_result($resaco,$conresaco,'rh90_ativa'))."','$this->rh90_ativa',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh90_instit"]) || $this->rh90_instit != "")
           $resac = db_query("insert into db_acount values($acount,3097,17544,'".AddSlashes(pg_result($resaco,$conresaco,'rh90_instit'))."','$this->rh90_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Geração da SEFIP nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh90_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Geração da SEFIP nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh90_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh90_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($rh90_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($rh90_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,17533,'$rh90_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3097,17533,'','".AddSlashes(pg_result($resaco,$iresaco,'rh90_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3097,17538,'','".AddSlashes(pg_result($resaco,$iresaco,'rh90_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3097,17539,'','".AddSlashes(pg_result($resaco,$iresaco,'rh90_mesusu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3097,17537,'','".AddSlashes(pg_result($resaco,$iresaco,'rh90_arquivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3097,17535,'','".AddSlashes(pg_result($resaco,$iresaco,'rh90_id_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3097,17534,'','".AddSlashes(pg_result($resaco,$iresaco,'rh90_datagera'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3097,17536,'','".AddSlashes(pg_result($resaco,$iresaco,'rh90_horagera'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3097,17540,'','".AddSlashes(pg_result($resaco,$iresaco,'rh90_valorcomp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3097,17541,'','".AddSlashes(pg_result($resaco,$iresaco,'rh90_compini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3097,17542,'','".AddSlashes(pg_result($resaco,$iresaco,'rh90_compfim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3097,17543,'','".AddSlashes(pg_result($resaco,$iresaco,'rh90_ativa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3097,17544,'','".AddSlashes(pg_result($resaco,$iresaco,'rh90_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from rhsefip
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($rh90_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " rh90_sequencial = $rh90_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Geração da SEFIP nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$rh90_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Geração da SEFIP nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$rh90_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$rh90_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:rhsefip";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $rh90_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhsefip ";
     $sql .= "      inner join db_config  on  db_config.codigo = rhsefip.rh90_instit";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = rhsefip.rh90_id_usuario";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = db_config.numcgm";
     $sql .= "      inner join db_tipoinstit  on  db_tipoinstit.db21_codtipo = db_config.db21_tipoinstit";
     $sql2 = "";
     if($dbwhere==""){
       if($rh90_sequencial!=null ){
         $sql2 .= " where rhsefip.rh90_sequencial = $rh90_sequencial "; 
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
   function sql_query_file ( $rh90_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhsefip ";
     $sql2 = "";
     if($dbwhere==""){
       if($rh90_sequencial!=null ){
         $sql2 .= " where rhsefip.rh90_sequencial = $rh90_sequencial "; 
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