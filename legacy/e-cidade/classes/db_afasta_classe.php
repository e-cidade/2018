<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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
//CLASSE DA ENTIDADE afasta
class cl_afasta { 
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
   var $r45_codigo = 0; 
   var $r45_anousu = 0; 
   var $r45_mesusu = 0; 
   var $r45_regist = 0; 
   var $r45_dtafas_dia = null; 
   var $r45_dtafas_mes = null; 
   var $r45_dtafas_ano = null; 
   var $r45_dtafas = null; 
   var $r45_dtreto_dia = null; 
   var $r45_dtreto_mes = null; 
   var $r45_dtreto_ano = null; 
   var $r45_dtreto = null; 
   var $r45_situac = 0; 
   var $r45_dtlanc_dia = null; 
   var $r45_dtlanc_mes = null; 
   var $r45_dtlanc_ano = null; 
   var $r45_dtlanc = null; 
   var $r45_codafa = null; 
   var $r45_codret = null; 
   var $r45_obs = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 r45_codigo = int8 = Código 
                 r45_anousu = int4 = Ano do Exercicio 
                 r45_mesusu = int4 = Mes do Exercicio 
                 r45_regist = int4 = Código do Servidor 
                 r45_dtafas = date = Início do Afastamento 
                 r45_dtreto = date = Final do Afastamento 
                 r45_situac = int4 = Situacao do funcionario 
                 r45_dtlanc = date = Data do Lancamento 
                 r45_codafa = varchar(2) = Afastamento Sefip 
                 r45_codret = varchar(2) = Retorno Sefip 
                 r45_obs = text = Observação 
                 ";
   //funcao construtor da classe 
   function cl_afasta() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("afasta"); 
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
       $this->r45_codigo = ($this->r45_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["r45_codigo"]:$this->r45_codigo);
       $this->r45_anousu = ($this->r45_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["r45_anousu"]:$this->r45_anousu);
       $this->r45_mesusu = ($this->r45_mesusu == ""?@$GLOBALS["HTTP_POST_VARS"]["r45_mesusu"]:$this->r45_mesusu);
       $this->r45_regist = ($this->r45_regist == ""?@$GLOBALS["HTTP_POST_VARS"]["r45_regist"]:$this->r45_regist);
       if($this->r45_dtafas == ""){
         $this->r45_dtafas_dia = ($this->r45_dtafas_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["r45_dtafas_dia"]:$this->r45_dtafas_dia);
         $this->r45_dtafas_mes = ($this->r45_dtafas_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["r45_dtafas_mes"]:$this->r45_dtafas_mes);
         $this->r45_dtafas_ano = ($this->r45_dtafas_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["r45_dtafas_ano"]:$this->r45_dtafas_ano);
         if($this->r45_dtafas_dia != ""){
            $this->r45_dtafas = $this->r45_dtafas_ano."-".$this->r45_dtafas_mes."-".$this->r45_dtafas_dia;
         }
       }
       if($this->r45_dtreto == ""){
         $this->r45_dtreto_dia = ($this->r45_dtreto_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["r45_dtreto_dia"]:$this->r45_dtreto_dia);
         $this->r45_dtreto_mes = ($this->r45_dtreto_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["r45_dtreto_mes"]:$this->r45_dtreto_mes);
         $this->r45_dtreto_ano = ($this->r45_dtreto_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["r45_dtreto_ano"]:$this->r45_dtreto_ano);
         if($this->r45_dtreto_dia != ""){
            $this->r45_dtreto = $this->r45_dtreto_ano."-".$this->r45_dtreto_mes."-".$this->r45_dtreto_dia;
         }
       }
       $this->r45_situac = ($this->r45_situac == ""?@$GLOBALS["HTTP_POST_VARS"]["r45_situac"]:$this->r45_situac);
       if($this->r45_dtlanc == ""){
         $this->r45_dtlanc_dia = ($this->r45_dtlanc_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["r45_dtlanc_dia"]:$this->r45_dtlanc_dia);
         $this->r45_dtlanc_mes = ($this->r45_dtlanc_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["r45_dtlanc_mes"]:$this->r45_dtlanc_mes);
         $this->r45_dtlanc_ano = ($this->r45_dtlanc_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["r45_dtlanc_ano"]:$this->r45_dtlanc_ano);
         if($this->r45_dtlanc_dia != ""){
            $this->r45_dtlanc = $this->r45_dtlanc_ano."-".$this->r45_dtlanc_mes."-".$this->r45_dtlanc_dia;
         }
       }
       $this->r45_codafa = ($this->r45_codafa == ""?@$GLOBALS["HTTP_POST_VARS"]["r45_codafa"]:$this->r45_codafa);
       $this->r45_codret = ($this->r45_codret == ""?@$GLOBALS["HTTP_POST_VARS"]["r45_codret"]:$this->r45_codret);
       $this->r45_obs = ($this->r45_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["r45_obs"]:$this->r45_obs);
     }else{
       $this->r45_codigo = ($this->r45_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["r45_codigo"]:$this->r45_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($r45_codigo){ 
      $this->atualizacampos();
     if($this->r45_anousu == null ){ 
       $this->erro_sql = " Campo Ano do Exercicio não informado.";
       $this->erro_campo = "r45_anousu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r45_mesusu == null ){ 
       $this->erro_sql = " Campo Mes do Exercicio não informado.";
       $this->erro_campo = "r45_mesusu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r45_regist == null ){ 
       $this->erro_sql = " Campo Código do Servidor não informado.";
       $this->erro_campo = "r45_regist";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r45_dtafas == null ){ 
       $this->erro_sql = " Campo Início do Afastamento não informado.";
       $this->erro_campo = "r45_dtafas_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r45_dtreto == null ){ 
       $this->r45_dtreto = "null";
     }
     if($this->r45_situac == null ){ 
       $this->erro_sql = " Campo Situacao do funcionario não informado.";
       $this->erro_campo = "r45_situac";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r45_dtlanc == null ){ 
       $this->erro_sql = " Campo Data do Lancamento não informado.";
       $this->erro_campo = "r45_dtlanc_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r45_codafa == null ){ 
       $this->erro_sql = " Campo Afastamento Sefip não informado.";
       $this->erro_campo = "r45_codafa";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r45_codret == null ){ 
       $this->erro_sql = " Campo Retorno Sefip não informado.";
       $this->erro_campo = "r45_codret";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($r45_codigo == "" || $r45_codigo == null ){
       $result = db_query("select nextval('afasta_r45_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: afasta_r45_codigo_seq do campo: r45_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->r45_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from afasta_r45_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $r45_codigo)){
         $this->erro_sql = " Campo r45_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->r45_codigo = $r45_codigo; 
       }
     }
     if(($this->r45_codigo == null) || ($this->r45_codigo == "") ){ 
       $this->erro_sql = " Campo r45_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into afasta(
                                       r45_codigo 
                                      ,r45_anousu 
                                      ,r45_mesusu 
                                      ,r45_regist 
                                      ,r45_dtafas 
                                      ,r45_dtreto 
                                      ,r45_situac 
                                      ,r45_dtlanc 
                                      ,r45_codafa 
                                      ,r45_codret 
                                      ,r45_obs 
                       )
                values (
                                $this->r45_codigo 
                               ,$this->r45_anousu 
                               ,$this->r45_mesusu 
                               ,$this->r45_regist 
                               ,".($this->r45_dtafas == "null" || $this->r45_dtafas == ""?"null":"'".$this->r45_dtafas."'")." 
                               ,".($this->r45_dtreto == "null" || $this->r45_dtreto == ""?"null":"'".$this->r45_dtreto."'")." 
                               ,$this->r45_situac 
                               ,".($this->r45_dtlanc == "null" || $this->r45_dtlanc == ""?"null":"'".$this->r45_dtlanc."'")." 
                               ,'$this->r45_codafa' 
                               ,'$this->r45_codret' 
                               ,'$this->r45_obs' 
                      )";
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Arquivo de afastamentos e retornos                 ($this->r45_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Arquivo de afastamentos e retornos                 já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Arquivo de afastamentos e retornos                 ($this->r45_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->r45_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->r45_codigo  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,8811,'$this->r45_codigo','I')");
         $resac = db_query("insert into db_acount values($acount,525,8811,'','".AddSlashes(pg_result($resaco,0,'r45_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,525,3633,'','".AddSlashes(pg_result($resaco,0,'r45_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,525,3634,'','".AddSlashes(pg_result($resaco,0,'r45_mesusu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,525,3635,'','".AddSlashes(pg_result($resaco,0,'r45_regist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,525,3636,'','".AddSlashes(pg_result($resaco,0,'r45_dtafas'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,525,3637,'','".AddSlashes(pg_result($resaco,0,'r45_dtreto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,525,3638,'','".AddSlashes(pg_result($resaco,0,'r45_situac'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,525,3640,'','".AddSlashes(pg_result($resaco,0,'r45_dtlanc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,525,4578,'','".AddSlashes(pg_result($resaco,0,'r45_codafa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,525,4579,'','".AddSlashes(pg_result($resaco,0,'r45_codret'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,525,14489,'','".AddSlashes(pg_result($resaco,0,'r45_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($r45_codigo=null) { 
      $this->atualizacampos();
     $sql = " update afasta set ";
     $virgula = "";
     if(trim($this->r45_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r45_codigo"])){ 
       $sql  .= $virgula." r45_codigo = $this->r45_codigo ";
       $virgula = ",";
       if(trim($this->r45_codigo) == null ){ 
         $this->erro_sql = " Campo Código não informado.";
         $this->erro_campo = "r45_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r45_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r45_anousu"])){ 
       $sql  .= $virgula." r45_anousu = $this->r45_anousu ";
       $virgula = ",";
       if(trim($this->r45_anousu) == null ){ 
         $this->erro_sql = " Campo Ano do Exercicio não informado.";
         $this->erro_campo = "r45_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r45_mesusu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r45_mesusu"])){ 
       $sql  .= $virgula." r45_mesusu = $this->r45_mesusu ";
       $virgula = ",";
       if(trim($this->r45_mesusu) == null ){ 
         $this->erro_sql = " Campo Mes do Exercicio não informado.";
         $this->erro_campo = "r45_mesusu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r45_regist)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r45_regist"])){ 
       $sql  .= $virgula." r45_regist = $this->r45_regist ";
       $virgula = ",";
       if(trim($this->r45_regist) == null ){ 
         $this->erro_sql = " Campo Código do Servidor não informado.";
         $this->erro_campo = "r45_regist";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r45_dtafas)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r45_dtafas_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["r45_dtafas_dia"] !="") ){ 
       $sql  .= $virgula." r45_dtafas = '$this->r45_dtafas' ";
       $virgula = ",";
       if(trim($this->r45_dtafas) == null ){ 
         $this->erro_sql = " Campo Início do Afastamento não informado.";
         $this->erro_campo = "r45_dtafas_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["r45_dtafas_dia"])){ 
         $sql  .= $virgula." r45_dtafas = null ";
         $virgula = ",";
         if(trim($this->r45_dtafas) == null ){ 
           $this->erro_sql = " Campo Início do Afastamento não informado.";
           $this->erro_campo = "r45_dtafas_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->r45_dtreto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r45_dtreto_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["r45_dtreto_dia"] !="") ){ 
       $sql  .= $virgula." r45_dtreto = '$this->r45_dtreto' ";
       $virgula = ",";
     } else if(trim($this->r45_dtreto)=="") {
        $sql  .= $virgula." r45_dtreto = null ";
        $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["r45_dtreto_dia"])){ 
         $sql  .= $virgula." r45_dtreto = null ";
         $virgula = ",";
       }
     }
     if(trim($this->r45_situac)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r45_situac"])){ 
       $sql  .= $virgula." r45_situac = $this->r45_situac ";
       $virgula = ",";
       if(trim($this->r45_situac) == null ){ 
         $this->erro_sql = " Campo Situacao do funcionario não informado.";
         $this->erro_campo = "r45_situac";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r45_dtlanc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r45_dtlanc_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["r45_dtlanc_dia"] !="") ){ 
       $sql  .= $virgula." r45_dtlanc = '$this->r45_dtlanc' ";
       $virgula = ",";
       if(trim($this->r45_dtlanc) == null ){ 
         $this->erro_sql = " Campo Data do Lancamento não informado.";
         $this->erro_campo = "r45_dtlanc_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["r45_dtlanc_dia"])){ 
         $sql  .= $virgula." r45_dtlanc = null ";
         $virgula = ",";
         if(trim($this->r45_dtlanc) == null ){ 
           $this->erro_sql = " Campo Data do Lancamento não informado.";
           $this->erro_campo = "r45_dtlanc_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->r45_codafa)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r45_codafa"])){ 
       $sql  .= $virgula." r45_codafa = '$this->r45_codafa' ";
       $virgula = ",";
       if(trim($this->r45_codafa) == null ){ 
         $this->erro_sql = " Campo Afastamento Sefip não informado.";
         $this->erro_campo = "r45_codafa";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r45_codret)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r45_codret"])){ 
       $sql  .= $virgula." r45_codret = '$this->r45_codret' ";
       $virgula = ",";
       if(trim($this->r45_codret) == null ){ 
         $this->erro_sql = " Campo Retorno Sefip não informado.";
         $this->erro_campo = "r45_codret";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r45_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r45_obs"])){ 
       $sql  .= $virgula." r45_obs = '$this->r45_obs' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($r45_codigo!=null){
       $sql .= " r45_codigo = $this->r45_codigo";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->r45_codigo));
       if($this->numrows>0){

         for($conresaco=0;$conresaco<$this->numrows;$conresaco++){

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,8811,'$this->r45_codigo','A')");
           if(isset($GLOBALS["HTTP_POST_VARS"]["r45_codigo"]) || $this->r45_codigo != "")
             $resac = db_query("insert into db_acount values($acount,525,8811,'".AddSlashes(pg_result($resaco,$conresaco,'r45_codigo'))."','$this->r45_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["r45_anousu"]) || $this->r45_anousu != "")
             $resac = db_query("insert into db_acount values($acount,525,3633,'".AddSlashes(pg_result($resaco,$conresaco,'r45_anousu'))."','$this->r45_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["r45_mesusu"]) || $this->r45_mesusu != "")
             $resac = db_query("insert into db_acount values($acount,525,3634,'".AddSlashes(pg_result($resaco,$conresaco,'r45_mesusu'))."','$this->r45_mesusu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["r45_regist"]) || $this->r45_regist != "")
             $resac = db_query("insert into db_acount values($acount,525,3635,'".AddSlashes(pg_result($resaco,$conresaco,'r45_regist'))."','$this->r45_regist',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["r45_dtafas"]) || $this->r45_dtafas != "")
             $resac = db_query("insert into db_acount values($acount,525,3636,'".AddSlashes(pg_result($resaco,$conresaco,'r45_dtafas'))."','$this->r45_dtafas',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["r45_dtreto"]) || $this->r45_dtreto != "")
             $resac = db_query("insert into db_acount values($acount,525,3637,'".AddSlashes(pg_result($resaco,$conresaco,'r45_dtreto'))."','$this->r45_dtreto',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["r45_situac"]) || $this->r45_situac != "")
             $resac = db_query("insert into db_acount values($acount,525,3638,'".AddSlashes(pg_result($resaco,$conresaco,'r45_situac'))."','$this->r45_situac',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["r45_dtlanc"]) || $this->r45_dtlanc != "")
             $resac = db_query("insert into db_acount values($acount,525,3640,'".AddSlashes(pg_result($resaco,$conresaco,'r45_dtlanc'))."','$this->r45_dtlanc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["r45_codafa"]) || $this->r45_codafa != "")
             $resac = db_query("insert into db_acount values($acount,525,4578,'".AddSlashes(pg_result($resaco,$conresaco,'r45_codafa'))."','$this->r45_codafa',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["r45_codret"]) || $this->r45_codret != "")
             $resac = db_query("insert into db_acount values($acount,525,4579,'".AddSlashes(pg_result($resaco,$conresaco,'r45_codret'))."','$this->r45_codret',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["r45_obs"]) || $this->r45_obs != "")
             $resac = db_query("insert into db_acount values($acount,525,14489,'".AddSlashes(pg_result($resaco,$conresaco,'r45_obs'))."','$this->r45_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Arquivo de afastamentos e retornos                 nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->r45_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Arquivo de afastamentos e retornos                 nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->r45_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->r45_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($r45_codigo=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if ($dbwhere==null || $dbwhere=="") {

         $resaco = $this->sql_record($this->sql_query_file($r45_codigo));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,8811,'$r45_codigo','E')");
           $resac  = db_query("insert into db_acount values($acount,525,8811,'','".AddSlashes(pg_result($resaco,$iresaco,'r45_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,525,3633,'','".AddSlashes(pg_result($resaco,$iresaco,'r45_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,525,3634,'','".AddSlashes(pg_result($resaco,$iresaco,'r45_mesusu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,525,3635,'','".AddSlashes(pg_result($resaco,$iresaco,'r45_regist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,525,3636,'','".AddSlashes(pg_result($resaco,$iresaco,'r45_dtafas'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,525,3637,'','".AddSlashes(pg_result($resaco,$iresaco,'r45_dtreto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,525,3638,'','".AddSlashes(pg_result($resaco,$iresaco,'r45_situac'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,525,3640,'','".AddSlashes(pg_result($resaco,$iresaco,'r45_dtlanc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,525,4578,'','".AddSlashes(pg_result($resaco,$iresaco,'r45_codafa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,525,4579,'','".AddSlashes(pg_result($resaco,$iresaco,'r45_codret'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,525,14489,'','".AddSlashes(pg_result($resaco,$iresaco,'r45_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from afasta
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($r45_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " r45_codigo = $r45_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Arquivo de afastamentos e retornos                 nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$r45_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Arquivo de afastamentos e retornos                 nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$r45_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$r45_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:afasta";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $r45_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from afasta ";
     $sql .= "      inner join rhpessoal  on  rhpessoal.rh01_regist = afasta.r45_regist";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = rhpessoal.rh01_numcgm";
     $sql .= "      inner join rhestcivil  on  rhestcivil.rh08_estciv = rhpessoal.rh01_estciv";
     $sql .= "      inner join rhraca  on  rhraca.rh18_raca = rhpessoal.rh01_raca";
     $sql .= "      left  join rhfuncao  on  rhfuncao.rh37_funcao = rhpessoal.rh01_funcao and  rhfuncao.rh37_instit = rhpessoal.rh01_instit";
     $sql .= "      inner join rhinstrucao  on  rhinstrucao.rh21_instru = rhpessoal.rh01_instru";
     $sql .= "      inner join rhnacionalidade  on  rhnacionalidade.rh06_nacionalidade = rhpessoal.rh01_nacion";
     $sql .= "      left  join rhsindicato  on  rhsindicato.rh116_sequencial = rhpessoal.rh01_rhsindicato";
     $sql2 = "";
     if($dbwhere==""){
       if($r45_codigo!=null ){
         $sql2 .= " where afasta.r45_codigo = $r45_codigo "; 
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
   function sql_query_file ( $r45_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from afasta ";
     $sql2 = "";
     if($dbwhere==""){
       if($r45_codigo!=null ){
         $sql2 .= " where afasta.r45_codigo = $r45_codigo "; 
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
   function atualiza_incluir (){
  	 $this->incluir();
   }

   // funcao do sql 
   function sql_query_left_join_assenta ( $r45_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from afasta ";
     $sql .= "      left join afastaassenta on afastaassenta.h81_afasta = afasta.r45_codigo";
     $sql2 = "";
     if($dbwhere==""){
       if($r45_codigo!=null ){
         $sql2 .= " where afasta.r45_codigo = $r45_codigo "; 
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
   function sql_query_pessoal ( $r45_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from afasta ";
     $sql .= "      inner join rhpessoal on rhpessoal.rh01_regist = afasta.r45_regist ";
     $sql .= "                          and rhpessoal.rh01_instit = ".db_getsession("DB_instit")." ";
     $sql .= "      inner join rhpessoalmov on rhpessoalmov.rh02_anousu = afasta.r45_anousu
                                           and rhpessoalmov.rh02_mesusu = afasta.r45_mesusu
                                           and rhpessoalmov.rh02_regist = afasta.r45_regist 
                                           and rhpessoal.rh01_instit = ".db_getsession("DB_instit")." ";
     $sql .= "      left join rhpesrescisao on rhpesrescisao.rh05_seqpes = rhpessoalmov.rh02_seqpes ";
     $sql2 = "";
     if($dbwhere==""){
       if($r45_codigo!=null ){
         $sql2 .= " where afasta.r45_codigo = $r45_codigo "; 
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
   * Monta o sql utilizado na rotina de Servidores Afastados
   * $iTipoResumo - integer -  
   *                          0 => Geral
   *                          1 => Orgao
   *                          2 => Lotação
   *                          3 => Matrícula
   *                          4 => Local de Trabalho
   *                          5 => Cargo
   *                          6 => Recurso
   * $sOrdem - string - Ordem dos registros
   * $sWhere - string Condição da query
   */
  public function sql_relatorioAfastados($iTipoResumo, $sCampos, $sOrdem, $sWhere, $iInstit = null) {


    $iAno = db_anofolha();
    $iMes = db_mesfolha();
    if (empty($iInstit)) {
      $iInstit = db_getsession('DB_instit');
    }

    $sSql  = "select {$sCampos}                                           \n";
    $sSql .= "   from afasta                                              \n";
    $sSql .= "        inner join rhpessoal    on rh01_regist = r45_regist \n";
    $sSql .= "        inner join cgm          on rh01_numcgm = z01_numcgm \n";
    $sSql .= "        inner join rhpessoalmov on rh02_anousu = r45_anousu \n";
    $sSql .= "                               and rh02_mesusu = r45_mesusu \n";
    $sSql .= "                               and rh02_regist = r45_regist \n";

    // lotacao
    if ($iTipoResumo == 2 || $iTipoResumo == 1) {
      $sSql .= " inner join rhlota on r70_codigo = rh02_lota \n";
    }

    //orgao
    if ($iTipoResumo == 1) {
      $sSql .= " inner join rhlotaexe on rh26_anousu = rh02_anousu      \n";
      $sSql .= "                     and rh26_codigo = r70_codigo       \n";
      $sSql .= " inner join orcunidade     on o41_anousu  = rh26_anousu \n";
      $sSql .= "                     and o41_orgao   = rh26_orgao       \n";
      $sSql .= "                     and o41_unidade = rh26_unidade     \n";
      $sSql .= " inner join orcorgao       on o40_anousu  = o41_anousu  \n";
      $sSql .= "                     and o40_orgao   = o41_orgao        \n";
    }


    //cargo
    if ($iTipoResumo == 5) {
      $sSql .= " inner join rhfuncao on rh37_funcao = rh02_funcao \n";
      $sSql .= "                    and rh37_instit = rh02_instit \n";
    }


    $sSql .= " where r45_anousu = {$iAno}     \n";
    $sSql .= "   and r45_mesusu = {$iMes}     \n";
    $sSql .= "   and rh02_instit = {$iInstit} \n";

    $sSql .=  $sWhere;

    $sSql .= $sOrdem;

    return $sSql;
  }

   function sql_query_func ( $r45_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from afasta                                                                                                                                         \n";
     $sql .= "      inner join rhpessoal        on  rhpessoal.rh01_regist              = afasta.r45_regist                                                         \n";
     $sql .= "      inner join rhpessoalmov     on  rhpessoal.rh01_regist              = rhpessoalmov.rh02_regist                                                  \n";
     $sql .= "                                 and  afasta.r45_anousu                  = rhpessoalmov.rh02_anousu                                                  \n";
     $sql .= "                                 and  afasta.r45_mesusu                  = rhpessoalmov.rh02_mesusu                                                  \n";
     $sql .= "      inner join cgm              on  cgm.z01_numcgm                     = rhpessoal.rh01_numcgm                                                     \n";
     $sql .= "      inner join rhestcivil       on  rhestcivil.rh08_estciv             = rhpessoal.rh01_estciv                                                     \n";
     $sql .= "      inner join rhraca           on  rhraca.rh18_raca                   = rhpessoal.rh01_raca                                                       \n";
     $sql .= "      left  join rhfuncao         on  rhfuncao.rh37_funcao               = rhpessoal.rh01_funcao and  rhfuncao.rh37_instit = rhpessoal.rh01_instit   \n";
     $sql .= "      inner join rhinstrucao      on  rhinstrucao.rh21_instru            = rhpessoal.rh01_instru                                                     \n";
     $sql .= "      inner join rhnacionalidade  on  rhnacionalidade.rh06_nacionalidade = rhpessoal.rh01_nacion                                                     \n";
     $sql .= "      left  join rhsindicato      on  rhsindicato.rh116_sequencial       = rhpessoal.rh01_rhsindicato                                                \n";
     $sql2 = "";
     if($dbwhere==""){
       if($r45_codigo!=null ){
         $sql2 .= " where afasta.r45_codigo = $r45_codigo "; 
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
