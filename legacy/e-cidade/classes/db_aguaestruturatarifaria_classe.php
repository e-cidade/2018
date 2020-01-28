<?php
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

//MODULO: agua
//CLASSE DA ENTIDADE aguaestruturatarifaria
class cl_aguaestruturatarifaria { 
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
   var $x37_sequencial = 0; 
   var $x37_aguaconsumotipo = 0; 
   var $x37_tipoestrutura = 0; 
   var $x37_valorinicial = 0; 
   var $x37_valorfinal = 0; 
   var $x37_valor = 0; 
   var $x37_percentual = 0;
   var $x37_aguacategoriaconsumo = 0;
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 x37_sequencial = int4 = Código 
                 x37_aguaconsumotipo = int4 = Código Tipo de Consumo 
                 x37_tipoestrutura = int4 = Tipo de Estrutura 
                 x37_valorinicial = int4 = Valor Inicial 
                 x37_valorfinal = int4 = Valor Final 
                 x37_valor = float4 = Valor 
                 x37_percentual = float4 = Percentual 
                 x37_aguacategoriaconsumo = int4 = Código da Categoria de Consumo 
                 ";
   //funcao construtor da classe 
   function cl_aguaestruturatarifaria() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("aguaestruturatarifaria"); 
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
       $this->x37_sequencial = ($this->x37_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["x37_sequencial"]:$this->x37_sequencial);
       $this->x37_aguaconsumotipo = ($this->x37_aguaconsumotipo == ""?@$GLOBALS["HTTP_POST_VARS"]["x37_aguaconsumotipo"]:$this->x37_aguaconsumotipo);
       $this->x37_tipoestrutura = ($this->x37_tipoestrutura == ""?@$GLOBALS["HTTP_POST_VARS"]["x37_tipoestrutura"]:$this->x37_tipoestrutura);
       $this->x37_valorinicial = ($this->x37_valorinicial == ""?@$GLOBALS["HTTP_POST_VARS"]["x37_valorinicial"]:$this->x37_valorinicial);
       $this->x37_valorfinal = ($this->x37_valorfinal == ""?@$GLOBALS["HTTP_POST_VARS"]["x37_valorfinal"]:$this->x37_valorfinal);
       $this->x37_valor = ($this->x37_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["x37_valor"]:$this->x37_valor);
       $this->x37_percentual = ($this->x37_percentual == ""?@$GLOBALS["HTTP_POST_VARS"]["x37_percentual"]:$this->x37_percentual);
       $this->x37_aguacategoriaconsumo = ($this->x37_aguacategoriaconsumo == ""?@$GLOBALS["HTTP_POST_VARS"]["x37_aguacategoriaconsumo"]:$this->x37_aguacategoriaconsumo);
     }else{
       $this->x37_sequencial = ($this->x37_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["x37_sequencial"]:$this->x37_sequencial);
     }
   }
   // funcao para Inclusão
   function incluir ($x37_sequencial){ 
      $this->atualizacampos();
     if($this->x37_aguaconsumotipo == null ){ 
       $this->erro_sql = " Campo Código Tipo de Consumo não informado.";
       $this->erro_campo = "x37_aguaconsumotipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->x37_tipoestrutura == null ){ 
       $this->erro_sql = " Campo Tipo de Estrutura não informado.";
       $this->erro_campo = "x37_tipoestrutura";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->x37_valorinicial == null ){ 
       $this->x37_valorinicial = "0";
     }
     if($this->x37_valorfinal == null ){ 
       $this->x37_valorfinal = "0";
     }
     if($this->x37_valor == null ){ 
       $this->x37_valor = "0";
     }
     if($this->x37_percentual == null ){ 
       $this->x37_percentual = "0";
     }
     if($this->x37_aguacategoriaconsumo == null ){ 
       $this->erro_sql = " Campo Código da Categoria de Consumo não informado.";
       $this->erro_campo = "x37_aguacategoriaconsumo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($x37_sequencial == "" || $x37_sequencial == null ){
       $result = db_query("select nextval('aguaestruturatarifaria_x37_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: aguaestruturatarifaria_x37_sequencial_seq do campo: x37_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->x37_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from aguaestruturatarifaria_x37_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $x37_sequencial)){
         $this->erro_sql = " Campo x37_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->x37_sequencial = $x37_sequencial; 
       }
     }
     if(($this->x37_sequencial == null) || ($this->x37_sequencial == "") ){ 
       $this->erro_sql = " Campo x37_sequencial não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into aguaestruturatarifaria(
                                       x37_sequencial 
                                      ,x37_aguaconsumotipo 
                                      ,x37_tipoestrutura 
                                      ,x37_valorinicial 
                                      ,x37_valorfinal 
                                      ,x37_valor 
                                      ,x37_percentual 
                                      ,x37_aguacategoriaconsumo 
                       )
                values (
                                $this->x37_sequencial 
                               ,$this->x37_aguaconsumotipo 
                               ,$this->x37_tipoestrutura 
                               ,$this->x37_valorinicial 
                               ,$this->x37_valorfinal 
                               ,$this->x37_valor 
                               ,$this->x37_percentual 
                               ,$this->x37_aguacategoriaconsumo 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Estrutura Tarifária ($this->x37_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Estrutura Tarifária já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Estrutura Tarifária ($this->x37_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->x37_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->x37_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,22063,'$this->x37_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3972,22063,'','".AddSlashes(pg_result($resaco,0,'x37_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3972,22064,'','".AddSlashes(pg_result($resaco,0,'x37_aguaconsumotipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3972,22065,'','".AddSlashes(pg_result($resaco,0,'x37_tipoestrutura'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3972,22066,'','".AddSlashes(pg_result($resaco,0,'x37_valorinicial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3972,22067,'','".AddSlashes(pg_result($resaco,0,'x37_valorfinal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3972,22068,'','".AddSlashes(pg_result($resaco,0,'x37_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3972,22069,'','".AddSlashes(pg_result($resaco,0,'x37_percentual'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3972,22075,'','".AddSlashes(pg_result($resaco,0,'x37_aguacategoriaconsumo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   public function alterar ($x37_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update aguaestruturatarifaria set ";
     $virgula = "";
     if(trim($this->x37_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x37_sequencial"])){ 
       $sql  .= $virgula." x37_sequencial = $this->x37_sequencial ";
       $virgula = ",";
       if(trim($this->x37_sequencial) == null ){ 
         $this->erro_sql = " Campo Código não informado.";
         $this->erro_campo = "x37_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x37_aguaconsumotipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x37_aguaconsumotipo"])){ 
       $sql  .= $virgula." x37_aguaconsumotipo = $this->x37_aguaconsumotipo ";
       $virgula = ",";
       if(trim($this->x37_aguaconsumotipo) == null ){ 
         $this->erro_sql = " Campo Código Tipo de Consumo não informado.";
         $this->erro_campo = "x37_aguaconsumotipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x37_tipoestrutura)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x37_tipoestrutura"])){ 
       $sql  .= $virgula." x37_tipoestrutura = $this->x37_tipoestrutura ";
       $virgula = ",";
       if(trim($this->x37_tipoestrutura) == null ){ 
         $this->erro_sql = " Campo Tipo de Estrutura não informado.";
         $this->erro_campo = "x37_tipoestrutura";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x37_valorinicial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x37_valorinicial"])){ 
        if(trim($this->x37_valorinicial)=="" && isset($GLOBALS["HTTP_POST_VARS"]["x37_valorinicial"])){ 
           $this->x37_valorinicial = "0" ; 
        } 
       $sql  .= $virgula." x37_valorinicial = $this->x37_valorinicial ";
       $virgula = ",";
     }
     if(trim($this->x37_valorfinal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x37_valorfinal"])){ 
        if(trim($this->x37_valorfinal)=="" && isset($GLOBALS["HTTP_POST_VARS"]["x37_valorfinal"])){ 
           $this->x37_valorfinal = "0" ; 
        } 
       $sql  .= $virgula." x37_valorfinal = $this->x37_valorfinal ";
       $virgula = ",";
     }
     if(trim($this->x37_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x37_valor"])){ 
        if(trim($this->x37_valor)=="" && isset($GLOBALS["HTTP_POST_VARS"]["x37_valor"])){ 
           $this->x37_valor = "0" ; 
        } 
       $sql  .= $virgula." x37_valor = $this->x37_valor ";
       $virgula = ",";
     }
     if(trim($this->x37_percentual)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x37_percentual"])){ 
        if(trim($this->x37_percentual)=="" && isset($GLOBALS["HTTP_POST_VARS"]["x37_percentual"])){ 
           $this->x37_percentual = "0" ; 
        } 
       $sql  .= $virgula." x37_percentual = $this->x37_percentual ";
       $virgula = ",";
     }
     if(trim($this->x37_aguacategoriaconsumo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x37_aguacategoriaconsumo"])){ 
       $sql  .= $virgula." x37_aguacategoriaconsumo = $this->x37_aguacategoriaconsumo ";
       $virgula = ",";
       if(trim($this->x37_aguacategoriaconsumo) == null ){ 
         $this->erro_sql = " Campo Código da Categoria de Consumo não informado.";
         $this->erro_campo = "x37_aguacategoriaconsumo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($x37_sequencial!=null){
       $sql .= " x37_sequencial = $this->x37_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->x37_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,22063,'$this->x37_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["x37_sequencial"]) || $this->x37_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3972,22063,'".AddSlashes(pg_result($resaco,$conresaco,'x37_sequencial'))."','$this->x37_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["x37_aguaconsumotipo"]) || $this->x37_aguaconsumotipo != "")
             $resac = db_query("insert into db_acount values($acount,3972,22064,'".AddSlashes(pg_result($resaco,$conresaco,'x37_aguaconsumotipo'))."','$this->x37_aguaconsumotipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["x37_tipoestrutura"]) || $this->x37_tipoestrutura != "")
             $resac = db_query("insert into db_acount values($acount,3972,22065,'".AddSlashes(pg_result($resaco,$conresaco,'x37_tipoestrutura'))."','$this->x37_tipoestrutura',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["x37_valorinicial"]) || $this->x37_valorinicial != "")
             $resac = db_query("insert into db_acount values($acount,3972,22066,'".AddSlashes(pg_result($resaco,$conresaco,'x37_valorinicial'))."','$this->x37_valorinicial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["x37_valorfinal"]) || $this->x37_valorfinal != "")
             $resac = db_query("insert into db_acount values($acount,3972,22067,'".AddSlashes(pg_result($resaco,$conresaco,'x37_valorfinal'))."','$this->x37_valorfinal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["x37_valor"]) || $this->x37_valor != "")
             $resac = db_query("insert into db_acount values($acount,3972,22068,'".AddSlashes(pg_result($resaco,$conresaco,'x37_valor'))."','$this->x37_valor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["x37_percentual"]) || $this->x37_percentual != "")
             $resac = db_query("insert into db_acount values($acount,3972,22069,'".AddSlashes(pg_result($resaco,$conresaco,'x37_percentual'))."','$this->x37_percentual',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["x37_aguacategoriaconsumo"]) || $this->x37_aguacategoriaconsumo != "")
             $resac = db_query("insert into db_acount values($acount,3972,22075,'".AddSlashes(pg_result($resaco,$conresaco,'x37_aguacategoriaconsumo'))."','$this->x37_aguacategoriaconsumo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Estrutura Tarifária não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->x37_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Estrutura Tarifária não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->x37_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->x37_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   public function excluir ($x37_sequencial=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($x37_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,22063,'$x37_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3972,22063,'','".AddSlashes(pg_result($resaco,$iresaco,'x37_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3972,22064,'','".AddSlashes(pg_result($resaco,$iresaco,'x37_aguaconsumotipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3972,22065,'','".AddSlashes(pg_result($resaco,$iresaco,'x37_tipoestrutura'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3972,22066,'','".AddSlashes(pg_result($resaco,$iresaco,'x37_valorinicial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3972,22067,'','".AddSlashes(pg_result($resaco,$iresaco,'x37_valorfinal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3972,22068,'','".AddSlashes(pg_result($resaco,$iresaco,'x37_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3972,22069,'','".AddSlashes(pg_result($resaco,$iresaco,'x37_percentual'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3972,22075,'','".AddSlashes(pg_result($resaco,$iresaco,'x37_aguacategoriaconsumo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from aguaestruturatarifaria
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($x37_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " x37_sequencial = $x37_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Estrutura Tarifária não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$x37_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Estrutura Tarifária não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$x37_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$x37_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:aguaestruturatarifaria";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   public function sql_query ($x37_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from aguaestruturatarifaria ";
     $sql .= "      inner join aguaconsumotipo  on  aguaconsumotipo.x25_codconsumotipo = aguaestruturatarifaria.x37_aguaconsumotipo";
     $sql .= "      inner join aguacategoriaconsumo  on  aguacategoriaconsumo.x13_sequencial = aguaestruturatarifaria.x37_aguacategoriaconsumo";
     $sql .= "      inner join histcalc  on  histcalc.k01_codigo = aguaconsumotipo.x25_codhist";
     $sql .= "      inner join tabrec  on  tabrec.k02_codigo = aguaconsumotipo.x25_receit";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($x37_sequencial)) {
         $sql2 .= " where aguaestruturatarifaria.x37_sequencial = $x37_sequencial "; 
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
   public function sql_query_file ($x37_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from aguaestruturatarifaria ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($x37_sequencial)){
         $sql2 .= " where aguaestruturatarifaria.x37_sequencial = $x37_sequencial "; 
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
