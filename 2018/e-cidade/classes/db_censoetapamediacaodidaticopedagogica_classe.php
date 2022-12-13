<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBselller Servicos de Informatica
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
//MODULO: escola
//CLASSE DA ENTIDADE censoetapamediacaodidaticopedagogica
class cl_censoetapamediacaodidaticopedagogica { 
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
   var $ed131_codigo = 0; 
   var $ed131_mediacaodidaticopedagogica = 0; 
   var $ed131_censoetapa = 0; 
   var $ed131_ano = 0; 
   var $ed131_regular = null; 
   var $ed131_especial = null; 
   var $ed131_eja = null; 
   var $ed131_profissional = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ed131_codigo = int4 = Código 
                 ed131_mediacaodidaticopedagogica = int4 = Mediação didático pedagógica 
                 ed131_censoetapa = int4 = Censo Etapa 
                 ed131_ano = int4 = Ano 
                 ed131_regular = varchar(1) = Regular 
                 ed131_especial = char(1) = Especial 
                 ed131_eja = char(1) = EJA 
                 ed131_profissional = char(1) = Profissional 
                 ";
   //funcao construtor da classe 
   function cl_censoetapamediacaodidaticopedagogica() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("censoetapamediacaodidaticopedagogica"); 
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
       $this->ed131_codigo = ($this->ed131_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed131_codigo"]:$this->ed131_codigo);
       $this->ed131_mediacaodidaticopedagogica = ($this->ed131_mediacaodidaticopedagogica == ""?@$GLOBALS["HTTP_POST_VARS"]["ed131_mediacaodidaticopedagogica"]:$this->ed131_mediacaodidaticopedagogica);
       $this->ed131_censoetapa = ($this->ed131_censoetapa == ""?@$GLOBALS["HTTP_POST_VARS"]["ed131_censoetapa"]:$this->ed131_censoetapa);
       $this->ed131_ano = ($this->ed131_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ed131_ano"]:$this->ed131_ano);
       $this->ed131_regular = ($this->ed131_regular == ""?@$GLOBALS["HTTP_POST_VARS"]["ed131_regular"]:$this->ed131_regular);
       $this->ed131_especial = ($this->ed131_especial == ""?@$GLOBALS["HTTP_POST_VARS"]["ed131_especial"]:$this->ed131_especial);
       $this->ed131_eja = ($this->ed131_eja == ""?@$GLOBALS["HTTP_POST_VARS"]["ed131_eja"]:$this->ed131_eja);
       $this->ed131_profissional = ($this->ed131_profissional == ""?@$GLOBALS["HTTP_POST_VARS"]["ed131_profissional"]:$this->ed131_profissional);
     }else{
       $this->ed131_codigo = ($this->ed131_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed131_codigo"]:$this->ed131_codigo);
     }
   }
   // funcao para Inclusão
   function incluir ($ed131_codigo){ 
      $this->atualizacampos();
     if($this->ed131_mediacaodidaticopedagogica == null ){ 
       $this->erro_sql = " Campo Mediação didático pedagógica não informado.";
       $this->erro_campo = "ed131_mediacaodidaticopedagogica";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed131_censoetapa == null ){ 
       $this->erro_sql = " Campo Censo Etapa não informado.";
       $this->erro_campo = "ed131_censoetapa";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed131_ano == null ){ 
       $this->erro_sql = " Campo Ano não informado.";
       $this->erro_campo = "ed131_ano";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed131_regular == null ){ 
       $this->erro_sql = " Campo Regular não informado.";
       $this->erro_campo = "ed131_regular";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed131_especial == null ){ 
       $this->erro_sql = " Campo Especial não informado.";
       $this->erro_campo = "ed131_especial";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed131_eja == null ){ 
       $this->erro_sql = " Campo EJA não informado.";
       $this->erro_campo = "ed131_eja";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed131_profissional == null ){ 
       $this->erro_sql = " Campo Profissional não informado.";
       $this->erro_campo = "ed131_profissional";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ed131_codigo == "" || $ed131_codigo == null ){
       $result = db_query("select nextval('censoetapamediacaodidaticopedagogica_ed131_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: censoetapamediacaodidaticopedagogica_ed131_codigo_seq do campo: ed131_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ed131_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from censoetapamediacaodidaticopedagogica_ed131_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $ed131_codigo)){
         $this->erro_sql = " Campo ed131_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ed131_codigo = $ed131_codigo; 
       }
     }
     if(($this->ed131_codigo == null) || ($this->ed131_codigo == "") ){ 
       $this->erro_sql = " Campo ed131_codigo não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into censoetapamediacaodidaticopedagogica(
                                       ed131_codigo 
                                      ,ed131_mediacaodidaticopedagogica 
                                      ,ed131_censoetapa 
                                      ,ed131_ano 
                                      ,ed131_regular 
                                      ,ed131_especial 
                                      ,ed131_eja 
                                      ,ed131_profissional 
                       )
                values (
                                $this->ed131_codigo 
                               ,$this->ed131_mediacaodidaticopedagogica 
                               ,$this->ed131_censoetapa 
                               ,$this->ed131_ano 
                               ,'$this->ed131_regular' 
                               ,'$this->ed131_especial' 
                               ,'$this->ed131_eja' 
                               ,'$this->ed131_profissional' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "censoetapamediacaodidaticopedagogica ($this->ed131_codigo) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "censoetapamediacaodidaticopedagogica já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "censoetapamediacaodidaticopedagogica ($this->ed131_codigo) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed131_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ed131_codigo  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,21050,'$this->ed131_codigo','I')");
         $resac = db_query("insert into db_acount values($acount,3793,21050,'','".AddSlashes(pg_result($resaco,0,'ed131_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3793,21051,'','".AddSlashes(pg_result($resaco,0,'ed131_mediacaodidaticopedagogica'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3793,21052,'','".AddSlashes(pg_result($resaco,0,'ed131_censoetapa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3793,21053,'','".AddSlashes(pg_result($resaco,0,'ed131_ano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3793,21121,'','".AddSlashes(pg_result($resaco,0,'ed131_regular'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3793,21122,'','".AddSlashes(pg_result($resaco,0,'ed131_especial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3793,21123,'','".AddSlashes(pg_result($resaco,0,'ed131_eja'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3793,21124,'','".AddSlashes(pg_result($resaco,0,'ed131_profissional'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   public function alterar ($ed131_codigo=null) { 
      $this->atualizacampos();
     $sql = " update censoetapamediacaodidaticopedagogica set ";
     $virgula = "";
     if(trim($this->ed131_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed131_codigo"])){ 
       $sql  .= $virgula." ed131_codigo = $this->ed131_codigo ";
       $virgula = ",";
       if(trim($this->ed131_codigo) == null ){ 
         $this->erro_sql = " Campo Código não informado.";
         $this->erro_campo = "ed131_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed131_mediacaodidaticopedagogica)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed131_mediacaodidaticopedagogica"])){ 
       $sql  .= $virgula." ed131_mediacaodidaticopedagogica = $this->ed131_mediacaodidaticopedagogica ";
       $virgula = ",";
       if(trim($this->ed131_mediacaodidaticopedagogica) == null ){ 
         $this->erro_sql = " Campo Mediação didático pedagógica não informado.";
         $this->erro_campo = "ed131_mediacaodidaticopedagogica";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed131_censoetapa)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed131_censoetapa"])){ 
       $sql  .= $virgula." ed131_censoetapa = $this->ed131_censoetapa ";
       $virgula = ",";
       if(trim($this->ed131_censoetapa) == null ){ 
         $this->erro_sql = " Campo Censo Etapa não informado.";
         $this->erro_campo = "ed131_censoetapa";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed131_ano)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed131_ano"])){ 
       $sql  .= $virgula." ed131_ano = $this->ed131_ano ";
       $virgula = ",";
       if(trim($this->ed131_ano) == null ){ 
         $this->erro_sql = " Campo Ano não informado.";
         $this->erro_campo = "ed131_ano";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed131_regular)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed131_regular"])){ 
       $sql  .= $virgula." ed131_regular = '$this->ed131_regular' ";
       $virgula = ",";
       if(trim($this->ed131_regular) == null ){ 
         $this->erro_sql = " Campo Regular não informado.";
         $this->erro_campo = "ed131_regular";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed131_especial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed131_especial"])){ 
       $sql  .= $virgula." ed131_especial = '$this->ed131_especial' ";
       $virgula = ",";
       if(trim($this->ed131_especial) == null ){ 
         $this->erro_sql = " Campo Especial não informado.";
         $this->erro_campo = "ed131_especial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed131_eja)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed131_eja"])){ 
       $sql  .= $virgula." ed131_eja = '$this->ed131_eja' ";
       $virgula = ",";
       if(trim($this->ed131_eja) == null ){ 
         $this->erro_sql = " Campo EJA não informado.";
         $this->erro_campo = "ed131_eja";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed131_profissional)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed131_profissional"])){ 
       $sql  .= $virgula." ed131_profissional = '$this->ed131_profissional' ";
       $virgula = ",";
       if(trim($this->ed131_profissional) == null ){ 
         $this->erro_sql = " Campo Profissional não informado.";
         $this->erro_campo = "ed131_profissional";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ed131_codigo!=null){
       $sql .= " ed131_codigo = $this->ed131_codigo";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ed131_codigo));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,21050,'$this->ed131_codigo','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed131_codigo"]) || $this->ed131_codigo != "")
             $resac = db_query("insert into db_acount values($acount,3793,21050,'".AddSlashes(pg_result($resaco,$conresaco,'ed131_codigo'))."','$this->ed131_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed131_mediacaodidaticopedagogica"]) || $this->ed131_mediacaodidaticopedagogica != "")
             $resac = db_query("insert into db_acount values($acount,3793,21051,'".AddSlashes(pg_result($resaco,$conresaco,'ed131_mediacaodidaticopedagogica'))."','$this->ed131_mediacaodidaticopedagogica',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed131_censoetapa"]) || $this->ed131_censoetapa != "")
             $resac = db_query("insert into db_acount values($acount,3793,21052,'".AddSlashes(pg_result($resaco,$conresaco,'ed131_censoetapa'))."','$this->ed131_censoetapa',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed131_ano"]) || $this->ed131_ano != "")
             $resac = db_query("insert into db_acount values($acount,3793,21053,'".AddSlashes(pg_result($resaco,$conresaco,'ed131_ano'))."','$this->ed131_ano',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed131_regular"]) || $this->ed131_regular != "")
             $resac = db_query("insert into db_acount values($acount,3793,21121,'".AddSlashes(pg_result($resaco,$conresaco,'ed131_regular'))."','$this->ed131_regular',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed131_especial"]) || $this->ed131_especial != "")
             $resac = db_query("insert into db_acount values($acount,3793,21122,'".AddSlashes(pg_result($resaco,$conresaco,'ed131_especial'))."','$this->ed131_especial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed131_eja"]) || $this->ed131_eja != "")
             $resac = db_query("insert into db_acount values($acount,3793,21123,'".AddSlashes(pg_result($resaco,$conresaco,'ed131_eja'))."','$this->ed131_eja',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed131_profissional"]) || $this->ed131_profissional != "")
             $resac = db_query("insert into db_acount values($acount,3793,21124,'".AddSlashes(pg_result($resaco,$conresaco,'ed131_profissional'))."','$this->ed131_profissional',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "censoetapamediacaodidaticopedagogica não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed131_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "censoetapamediacaodidaticopedagogica não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed131_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed131_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   public function excluir ($ed131_codigo=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($ed131_codigo));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,21050,'$ed131_codigo','E')");
           $resac  = db_query("insert into db_acount values($acount,3793,21050,'','".AddSlashes(pg_result($resaco,$iresaco,'ed131_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3793,21051,'','".AddSlashes(pg_result($resaco,$iresaco,'ed131_mediacaodidaticopedagogica'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3793,21052,'','".AddSlashes(pg_result($resaco,$iresaco,'ed131_censoetapa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3793,21053,'','".AddSlashes(pg_result($resaco,$iresaco,'ed131_ano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3793,21121,'','".AddSlashes(pg_result($resaco,$iresaco,'ed131_regular'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3793,21122,'','".AddSlashes(pg_result($resaco,$iresaco,'ed131_especial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3793,21123,'','".AddSlashes(pg_result($resaco,$iresaco,'ed131_eja'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3793,21124,'','".AddSlashes(pg_result($resaco,$iresaco,'ed131_profissional'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from censoetapamediacaodidaticopedagogica
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($ed131_codigo)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " ed131_codigo = $ed131_codigo ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "censoetapamediacaodidaticopedagogica não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed131_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "censoetapamediacaodidaticopedagogica não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed131_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ed131_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao do recordset 
   public function sql_record($sql) { 
     $result = db_query($sql);
     if (!$result) {
       $this->numrows    = 0;
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Erro ao selecionar os registros.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $this->numrows = pg_num_rows($result);
      if ($this->numrows == 0) {
        $this->erro_banco = "";
        $this->erro_sql   = "Record Vazio na Tabela:censoetapamediacaodidaticopedagogica";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   public function sql_query ($ed131_codigo = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from censoetapamediacaodidaticopedagogica ";
     $sql .= "      inner join censoetapa  on  censoetapa.ed266_i_codigo = censoetapamediacaodidaticopedagogica.ed131_censoetapa ";
     $sql .= "                            and  censoetapa.ed266_ano = censoetapamediacaodidaticopedagogica.ed131_ano ";
     $sql .= "      inner join mediacaodidaticopedagogica  on  mediacaodidaticopedagogica.ed130_codigo = censoetapamediacaodidaticopedagogica.ed131_mediacaodidaticopedagogica";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($ed131_codigo)) {
         $sql2 .= " where censoetapamediacaodidaticopedagogica.ed131_codigo = $ed131_codigo "; 
       } 
     } else if (!empty($dbwhere)) {
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if (!empty($ordem)) {
       $sql .= " order by {$ordem}";
     }
     return $sql;
  }
   // funcao do sql 
   public function sql_query_file ($ed131_codigo = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from censoetapamediacaodidaticopedagogica ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($ed131_codigo)){
         $sql2 .= " where censoetapamediacaodidaticopedagogica.ed131_codigo = $ed131_codigo "; 
       } 
     } else if (!empty($dbwhere)) {
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if (!empty($ordem)) {
       $sql .= " order by {$ordem}";
     }
     return $sql;
  }

}
