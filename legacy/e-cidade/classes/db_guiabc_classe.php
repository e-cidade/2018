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

//MODULO: dbicms
//CLASSE DA ENTIDADE guiabc
class cl_guiabc { 
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
   var $anousu = 0; 
   var $tr = null; 
   var $cgcter = null; 
   var $cgcte = null; 
   var $cnpj = null; 
   var $perini_dia = null; 
   var $perini_mes = null; 
   var $perini_ano = null; 
   var $perini = null; 
   var $perfin_dia = null; 
   var $perfin_mes = null; 
   var $perfin_ano = null; 
   var $perfin = null; 
   var $catego = null; 
   var $substi = null; 
   var $fatur = 0; 
   var $origem = null; 
   var $transm_dia = null; 
   var $transm_mes = null; 
   var $transm_ano = null; 
   var $transm = null; 
   var $nire = null; 
   var $contab = null; 
   var $ddd = null; 
   var $telef = null; 
   var $z01_cgccpf = null; 
   var $anexo = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 anousu = int4 = Exercício 
                 tr = char(2) = Tipo de Registro 
                 cgcter = char(3) = Codigo do Município 
                 cgcte = char(7) = Cadastro no Tesouro do Estado 
                 cnpj = char(14) = Inscrição Federal 
                 perini = date = Período Inicial 
                 perfin = date = Período Final 
                 catego = char(1) = Categoria 
                 substi = char(1) = Substitutiva 
                 fatur = float8 = Faturamento 
                 origem = char(1) = Forma de Entrega 
                 transm = date = Transmissão 
                 nire = char(11) = NIRE 
                 contab = char(30) = Contabilista 
                 ddd = char(2) = DDD 
                 telef = char(11) = Telefone 
                 z01_cgccpf = varchar(14) = CNPJ/CPF 
                 anexo = char(1) = Anexo 
                 ";
   //funcao construtor da classe 
   function cl_guiabc() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("guiabc"); 
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
       $this->anousu = ($this->anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["anousu"]:$this->anousu);
       $this->tr = ($this->tr == ""?@$GLOBALS["HTTP_POST_VARS"]["tr"]:$this->tr);
       $this->cgcter = ($this->cgcter == ""?@$GLOBALS["HTTP_POST_VARS"]["cgcter"]:$this->cgcter);
       $this->cgcte = ($this->cgcte == ""?@$GLOBALS["HTTP_POST_VARS"]["cgcte"]:$this->cgcte);
       $this->cnpj = ($this->cnpj == ""?@$GLOBALS["HTTP_POST_VARS"]["cnpj"]:$this->cnpj);
       if($this->perini == ""){
         $this->perini_dia = ($this->perini_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["perini_dia"]:$this->perini_dia);
         $this->perini_mes = ($this->perini_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["perini_mes"]:$this->perini_mes);
         $this->perini_ano = ($this->perini_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["perini_ano"]:$this->perini_ano);
         if($this->perini_dia != ""){
            $this->perini = $this->perini_ano."-".$this->perini_mes."-".$this->perini_dia;
         }
       }
       if($this->perfin == ""){
         $this->perfin_dia = ($this->perfin_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["perfin_dia"]:$this->perfin_dia);
         $this->perfin_mes = ($this->perfin_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["perfin_mes"]:$this->perfin_mes);
         $this->perfin_ano = ($this->perfin_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["perfin_ano"]:$this->perfin_ano);
         if($this->perfin_dia != ""){
            $this->perfin = $this->perfin_ano."-".$this->perfin_mes."-".$this->perfin_dia;
         }
       }
       $this->catego = ($this->catego == ""?@$GLOBALS["HTTP_POST_VARS"]["catego"]:$this->catego);
       $this->substi = ($this->substi == ""?@$GLOBALS["HTTP_POST_VARS"]["substi"]:$this->substi);
       $this->fatur = ($this->fatur == ""?@$GLOBALS["HTTP_POST_VARS"]["fatur"]:$this->fatur);
       $this->origem = ($this->origem == ""?@$GLOBALS["HTTP_POST_VARS"]["origem"]:$this->origem);
       if($this->transm == ""){
         $this->transm_dia = ($this->transm_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["transm_dia"]:$this->transm_dia);
         $this->transm_mes = ($this->transm_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["transm_mes"]:$this->transm_mes);
         $this->transm_ano = ($this->transm_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["transm_ano"]:$this->transm_ano);
         if($this->transm_dia != ""){
            $this->transm = $this->transm_ano."-".$this->transm_mes."-".$this->transm_dia;
         }
       }
       $this->nire = ($this->nire == ""?@$GLOBALS["HTTP_POST_VARS"]["nire"]:$this->nire);
       $this->contab = ($this->contab == ""?@$GLOBALS["HTTP_POST_VARS"]["contab"]:$this->contab);
       $this->ddd = ($this->ddd == ""?@$GLOBALS["HTTP_POST_VARS"]["ddd"]:$this->ddd);
       $this->telef = ($this->telef == ""?@$GLOBALS["HTTP_POST_VARS"]["telef"]:$this->telef);
       $this->z01_cgccpf = ($this->z01_cgccpf == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_cgccpf"]:$this->z01_cgccpf);
       $this->anexo = ($this->anexo == ""?@$GLOBALS["HTTP_POST_VARS"]["anexo"]:$this->anexo);
     }else{
       $this->anousu = ($this->anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["anousu"]:$this->anousu);
       $this->tr = ($this->tr == ""?@$GLOBALS["HTTP_POST_VARS"]["tr"]:$this->tr);
       $this->cgcter = ($this->cgcter == ""?@$GLOBALS["HTTP_POST_VARS"]["cgcter"]:$this->cgcter);
       $this->cgcte = ($this->cgcte == ""?@$GLOBALS["HTTP_POST_VARS"]["cgcte"]:$this->cgcte);
     }
   }
   // funcao para inclusao
   function incluir ($anousu,$tr,$cgcter,$cgcte){ 
      $this->atualizacampos();
     if($this->cnpj == null ){ 
       $this->erro_sql = " Campo Inscrição Federal nao Informado.";
       $this->erro_campo = "cnpj";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->perini == null ){ 
       $this->erro_sql = " Campo Período Inicial nao Informado.";
       $this->erro_campo = "perini_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->perfin == null ){ 
       $this->erro_sql = " Campo Período Final nao Informado.";
       $this->erro_campo = "perfin_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->catego == null ){ 
       $this->erro_sql = " Campo Categoria nao Informado.";
       $this->erro_campo = "catego";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->substi == null ){ 
       $this->erro_sql = " Campo Substitutiva nao Informado.";
       $this->erro_campo = "substi";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->fatur == null ){ 
       $this->erro_sql = " Campo Faturamento nao Informado.";
       $this->erro_campo = "fatur";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->origem == null ){ 
       $this->erro_sql = " Campo Forma de Entrega nao Informado.";
       $this->erro_campo = "origem";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->transm == null ){ 
       $this->erro_sql = " Campo Transmissão nao Informado.";
       $this->erro_campo = "transm_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->nire == null ){ 
       $this->erro_sql = " Campo NIRE nao Informado.";
       $this->erro_campo = "nire";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->contab == null ){ 
       $this->erro_sql = " Campo Contabilista nao Informado.";
       $this->erro_campo = "contab";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ddd == null ){ 
       $this->erro_sql = " Campo DDD nao Informado.";
       $this->erro_campo = "ddd";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->telef == null ){ 
       $this->erro_sql = " Campo Telefone nao Informado.";
       $this->erro_campo = "telef";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->anexo == null ){ 
       $this->erro_sql = " Campo Anexo nao Informado.";
       $this->erro_campo = "anexo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->anousu = $anousu; 
       $this->tr = $tr; 
       $this->cgcter = $cgcter; 
       $this->cgcte = $cgcte; 
     if(($this->anousu == null) || ($this->anousu == "") ){ 
       $this->erro_sql = " Campo anousu nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->tr == null) || ($this->tr == "") ){ 
       $this->erro_sql = " Campo tr nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->cgcter == null) || ($this->cgcter == "") ){ 
       $this->erro_sql = " Campo cgcter nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->cgcte == null) || ($this->cgcte == "") ){ 
       $this->erro_sql = " Campo cgcte nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into guiabc(
                                       anousu 
                                      ,tr 
                                      ,cgcter 
                                      ,cgcte 
                                      ,cnpj 
                                      ,perini 
                                      ,perfin 
                                      ,catego 
                                      ,substi 
                                      ,fatur 
                                      ,origem 
                                      ,transm 
                                      ,nire 
                                      ,contab 
                                      ,ddd 
                                      ,telef 
                                      ,z01_cgccpf 
                                      ,anexo 
                       )
                values (
                                $this->anousu 
                               ,'$this->tr' 
                               ,'$this->cgcter' 
                               ,'$this->cgcte' 
                               ,'$this->cnpj' 
                               ,".($this->perini == "null" || $this->perini == ""?"null":"'".$this->perini."'")." 
                               ,".($this->perfin == "null" || $this->perfin == ""?"null":"'".$this->perfin."'")." 
                               ,'$this->catego' 
                               ,'$this->substi' 
                               ,$this->fatur 
                               ,'$this->origem' 
                               ,".($this->transm == "null" || $this->transm == ""?"null":"'".$this->transm."'")." 
                               ,'$this->nire' 
                               ,'$this->contab' 
                               ,'$this->ddd' 
                               ,'$this->telef' 
                               ,'$this->z01_cgccpf' 
                               ,'$this->anexo' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Guiabc ($this->anousu."-".$this->tr."-".$this->cgcter."-".$this->cgcte) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Guiabc já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Guiabc ($this->anousu."-".$this->tr."-".$this->cgcter."-".$this->cgcte) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->anousu."-".$this->tr."-".$this->cgcter."-".$this->cgcte;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->anousu,$this->tr,$this->cgcter,$this->cgcte));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,1019,'$this->anousu','I')");
       $resac = db_query("insert into db_acountkey values($acount,2304,'$this->tr','I')");
       $resac = db_query("insert into db_acountkey values($acount,2275,'$this->cgcter','I')");
       $resac = db_query("insert into db_acountkey values($acount,2280,'$this->cgcte','I')");
       $resac = db_query("insert into db_acount values($acount,363,1019,'','".AddSlashes(pg_result($resaco,0,'anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,363,2304,'','".AddSlashes(pg_result($resaco,0,'tr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,363,2275,'','".AddSlashes(pg_result($resaco,0,'cgcter'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,363,2280,'','".AddSlashes(pg_result($resaco,0,'cgcte'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,363,2281,'','".AddSlashes(pg_result($resaco,0,'cnpj'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,363,2282,'','".AddSlashes(pg_result($resaco,0,'perini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,363,2294,'','".AddSlashes(pg_result($resaco,0,'perfin'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,363,2295,'','".AddSlashes(pg_result($resaco,0,'catego'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,363,2296,'','".AddSlashes(pg_result($resaco,0,'substi'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,363,2297,'','".AddSlashes(pg_result($resaco,0,'fatur'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,363,2298,'','".AddSlashes(pg_result($resaco,0,'origem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,363,2299,'','".AddSlashes(pg_result($resaco,0,'transm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,363,2300,'','".AddSlashes(pg_result($resaco,0,'nire'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,363,2301,'','".AddSlashes(pg_result($resaco,0,'contab'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,363,2302,'','".AddSlashes(pg_result($resaco,0,'ddd'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,363,457,'','".AddSlashes(pg_result($resaco,0,'telef'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,363,1126,'','".AddSlashes(pg_result($resaco,0,'z01_cgccpf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,363,2303,'','".AddSlashes(pg_result($resaco,0,'anexo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($anousu=null,$tr=null,$cgcter=null,$cgcte=null) { 
      $this->atualizacampos();
     $sql = " update guiabc set ";
     $virgula = "";
     if(trim($this->anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["anousu"])){ 
       $sql  .= $virgula." anousu = $this->anousu ";
       $virgula = ",";
       if(trim($this->anousu) == null ){ 
         $this->erro_sql = " Campo Exercício nao Informado.";
         $this->erro_campo = "anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->tr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tr"])){ 
       $sql  .= $virgula." tr = '$this->tr' ";
       $virgula = ",";
       if(trim($this->tr) == null ){ 
         $this->erro_sql = " Campo Tipo de Registro nao Informado.";
         $this->erro_campo = "tr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cgcter)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cgcter"])){ 
       $sql  .= $virgula." cgcter = '$this->cgcter' ";
       $virgula = ",";
       if(trim($this->cgcter) == null ){ 
         $this->erro_sql = " Campo Codigo do Município nao Informado.";
         $this->erro_campo = "cgcter";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cgcte)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cgcte"])){ 
       $sql  .= $virgula." cgcte = '$this->cgcte' ";
       $virgula = ",";
       if(trim($this->cgcte) == null ){ 
         $this->erro_sql = " Campo Cadastro no Tesouro do Estado nao Informado.";
         $this->erro_campo = "cgcte";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cnpj)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cnpj"])){ 
       $sql  .= $virgula." cnpj = '$this->cnpj' ";
       $virgula = ",";
       if(trim($this->cnpj) == null ){ 
         $this->erro_sql = " Campo Inscrição Federal nao Informado.";
         $this->erro_campo = "cnpj";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->perini)!="" || isset($GLOBALS["HTTP_POST_VARS"]["perini_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["perini_dia"] !="") ){ 
       $sql  .= $virgula." perini = '$this->perini' ";
       $virgula = ",";
       if(trim($this->perini) == null ){ 
         $this->erro_sql = " Campo Período Inicial nao Informado.";
         $this->erro_campo = "perini_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["perini_dia"])){ 
         $sql  .= $virgula." perini = null ";
         $virgula = ",";
         if(trim($this->perini) == null ){ 
           $this->erro_sql = " Campo Período Inicial nao Informado.";
           $this->erro_campo = "perini_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->perfin)!="" || isset($GLOBALS["HTTP_POST_VARS"]["perfin_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["perfin_dia"] !="") ){ 
       $sql  .= $virgula." perfin = '$this->perfin' ";
       $virgula = ",";
       if(trim($this->perfin) == null ){ 
         $this->erro_sql = " Campo Período Final nao Informado.";
         $this->erro_campo = "perfin_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["perfin_dia"])){ 
         $sql  .= $virgula." perfin = null ";
         $virgula = ",";
         if(trim($this->perfin) == null ){ 
           $this->erro_sql = " Campo Período Final nao Informado.";
           $this->erro_campo = "perfin_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->catego)!="" || isset($GLOBALS["HTTP_POST_VARS"]["catego"])){ 
       $sql  .= $virgula." catego = '$this->catego' ";
       $virgula = ",";
       if(trim($this->catego) == null ){ 
         $this->erro_sql = " Campo Categoria nao Informado.";
         $this->erro_campo = "catego";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->substi)!="" || isset($GLOBALS["HTTP_POST_VARS"]["substi"])){ 
       $sql  .= $virgula." substi = '$this->substi' ";
       $virgula = ",";
       if(trim($this->substi) == null ){ 
         $this->erro_sql = " Campo Substitutiva nao Informado.";
         $this->erro_campo = "substi";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->fatur)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fatur"])){ 
       $sql  .= $virgula." fatur = $this->fatur ";
       $virgula = ",";
       if(trim($this->fatur) == null ){ 
         $this->erro_sql = " Campo Faturamento nao Informado.";
         $this->erro_campo = "fatur";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->origem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["origem"])){ 
       $sql  .= $virgula." origem = '$this->origem' ";
       $virgula = ",";
       if(trim($this->origem) == null ){ 
         $this->erro_sql = " Campo Forma de Entrega nao Informado.";
         $this->erro_campo = "origem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->transm)!="" || isset($GLOBALS["HTTP_POST_VARS"]["transm_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["transm_dia"] !="") ){ 
       $sql  .= $virgula." transm = '$this->transm' ";
       $virgula = ",";
       if(trim($this->transm) == null ){ 
         $this->erro_sql = " Campo Transmissão nao Informado.";
         $this->erro_campo = "transm_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["transm_dia"])){ 
         $sql  .= $virgula." transm = null ";
         $virgula = ",";
         if(trim($this->transm) == null ){ 
           $this->erro_sql = " Campo Transmissão nao Informado.";
           $this->erro_campo = "transm_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->nire)!="" || isset($GLOBALS["HTTP_POST_VARS"]["nire"])){ 
       $sql  .= $virgula." nire = '$this->nire' ";
       $virgula = ",";
       if(trim($this->nire) == null ){ 
         $this->erro_sql = " Campo NIRE nao Informado.";
         $this->erro_campo = "nire";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->contab)!="" || isset($GLOBALS["HTTP_POST_VARS"]["contab"])){ 
       $sql  .= $virgula." contab = '$this->contab' ";
       $virgula = ",";
       if(trim($this->contab) == null ){ 
         $this->erro_sql = " Campo Contabilista nao Informado.";
         $this->erro_campo = "contab";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ddd)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ddd"])){ 
       $sql  .= $virgula." ddd = '$this->ddd' ";
       $virgula = ",";
       if(trim($this->ddd) == null ){ 
         $this->erro_sql = " Campo DDD nao Informado.";
         $this->erro_campo = "ddd";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->telef)!="" || isset($GLOBALS["HTTP_POST_VARS"]["telef"])){ 
       $sql  .= $virgula." telef = '$this->telef' ";
       $virgula = ",";
       if(trim($this->telef) == null ){ 
         $this->erro_sql = " Campo Telefone nao Informado.";
         $this->erro_campo = "telef";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->z01_cgccpf)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z01_cgccpf"])){ 
       $sql  .= $virgula." z01_cgccpf = '$this->z01_cgccpf' ";
       $virgula = ",";
     }
     if(trim($this->anexo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["anexo"])){ 
       $sql  .= $virgula." anexo = '$this->anexo' ";
       $virgula = ",";
       if(trim($this->anexo) == null ){ 
         $this->erro_sql = " Campo Anexo nao Informado.";
         $this->erro_campo = "anexo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($anousu!=null){
       $sql .= " anousu = $this->anousu";
     }
     if($tr!=null){
       $sql .= " and  tr = '$this->tr'";
     }
     if($cgcter!=null){
       $sql .= " and  cgcter = '$this->cgcter'";
     }
     if($cgcte!=null){
       $sql .= " and  cgcte = '$this->cgcte'";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->anousu,$this->tr,$this->cgcter,$this->cgcte));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1019,'$this->anousu','A')");
         $resac = db_query("insert into db_acountkey values($acount,2304,'$this->tr','A')");
         $resac = db_query("insert into db_acountkey values($acount,2275,'$this->cgcter','A')");
         $resac = db_query("insert into db_acountkey values($acount,2280,'$this->cgcte','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["anousu"]))
           $resac = db_query("insert into db_acount values($acount,363,1019,'".AddSlashes(pg_result($resaco,$conresaco,'anousu'))."','$this->anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["tr"]))
           $resac = db_query("insert into db_acount values($acount,363,2304,'".AddSlashes(pg_result($resaco,$conresaco,'tr'))."','$this->tr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cgcter"]))
           $resac = db_query("insert into db_acount values($acount,363,2275,'".AddSlashes(pg_result($resaco,$conresaco,'cgcter'))."','$this->cgcter',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cgcte"]))
           $resac = db_query("insert into db_acount values($acount,363,2280,'".AddSlashes(pg_result($resaco,$conresaco,'cgcte'))."','$this->cgcte',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cnpj"]))
           $resac = db_query("insert into db_acount values($acount,363,2281,'".AddSlashes(pg_result($resaco,$conresaco,'cnpj'))."','$this->cnpj',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["perini"]))
           $resac = db_query("insert into db_acount values($acount,363,2282,'".AddSlashes(pg_result($resaco,$conresaco,'perini'))."','$this->perini',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["perfin"]))
           $resac = db_query("insert into db_acount values($acount,363,2294,'".AddSlashes(pg_result($resaco,$conresaco,'perfin'))."','$this->perfin',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["catego"]))
           $resac = db_query("insert into db_acount values($acount,363,2295,'".AddSlashes(pg_result($resaco,$conresaco,'catego'))."','$this->catego',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["substi"]))
           $resac = db_query("insert into db_acount values($acount,363,2296,'".AddSlashes(pg_result($resaco,$conresaco,'substi'))."','$this->substi',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["fatur"]))
           $resac = db_query("insert into db_acount values($acount,363,2297,'".AddSlashes(pg_result($resaco,$conresaco,'fatur'))."','$this->fatur',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["origem"]))
           $resac = db_query("insert into db_acount values($acount,363,2298,'".AddSlashes(pg_result($resaco,$conresaco,'origem'))."','$this->origem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["transm"]))
           $resac = db_query("insert into db_acount values($acount,363,2299,'".AddSlashes(pg_result($resaco,$conresaco,'transm'))."','$this->transm',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["nire"]))
           $resac = db_query("insert into db_acount values($acount,363,2300,'".AddSlashes(pg_result($resaco,$conresaco,'nire'))."','$this->nire',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["contab"]))
           $resac = db_query("insert into db_acount values($acount,363,2301,'".AddSlashes(pg_result($resaco,$conresaco,'contab'))."','$this->contab',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ddd"]))
           $resac = db_query("insert into db_acount values($acount,363,2302,'".AddSlashes(pg_result($resaco,$conresaco,'ddd'))."','$this->ddd',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["telef"]))
           $resac = db_query("insert into db_acount values($acount,363,457,'".AddSlashes(pg_result($resaco,$conresaco,'telef'))."','$this->telef',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["z01_cgccpf"]))
           $resac = db_query("insert into db_acount values($acount,363,1126,'".AddSlashes(pg_result($resaco,$conresaco,'z01_cgccpf'))."','$this->z01_cgccpf',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["anexo"]))
           $resac = db_query("insert into db_acount values($acount,363,2303,'".AddSlashes(pg_result($resaco,$conresaco,'anexo'))."','$this->anexo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Guiabc nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->anousu."-".$this->tr."-".$this->cgcter."-".$this->cgcte;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Guiabc nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->anousu."-".$this->tr."-".$this->cgcter."-".$this->cgcte;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->anousu."-".$this->tr."-".$this->cgcter."-".$this->cgcte;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($anousu=null,$tr=null,$cgcter=null,$cgcte=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($anousu,$tr,$cgcter,$cgcte));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1019,'$anousu','E')");
         $resac = db_query("insert into db_acountkey values($acount,2304,'$tr','E')");
         $resac = db_query("insert into db_acountkey values($acount,2275,'$cgcter','E')");
         $resac = db_query("insert into db_acountkey values($acount,2280,'$cgcte','E')");
         $resac = db_query("insert into db_acount values($acount,363,1019,'','".AddSlashes(pg_result($resaco,$iresaco,'anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,363,2304,'','".AddSlashes(pg_result($resaco,$iresaco,'tr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,363,2275,'','".AddSlashes(pg_result($resaco,$iresaco,'cgcter'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,363,2280,'','".AddSlashes(pg_result($resaco,$iresaco,'cgcte'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,363,2281,'','".AddSlashes(pg_result($resaco,$iresaco,'cnpj'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,363,2282,'','".AddSlashes(pg_result($resaco,$iresaco,'perini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,363,2294,'','".AddSlashes(pg_result($resaco,$iresaco,'perfin'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,363,2295,'','".AddSlashes(pg_result($resaco,$iresaco,'catego'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,363,2296,'','".AddSlashes(pg_result($resaco,$iresaco,'substi'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,363,2297,'','".AddSlashes(pg_result($resaco,$iresaco,'fatur'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,363,2298,'','".AddSlashes(pg_result($resaco,$iresaco,'origem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,363,2299,'','".AddSlashes(pg_result($resaco,$iresaco,'transm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,363,2300,'','".AddSlashes(pg_result($resaco,$iresaco,'nire'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,363,2301,'','".AddSlashes(pg_result($resaco,$iresaco,'contab'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,363,2302,'','".AddSlashes(pg_result($resaco,$iresaco,'ddd'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,363,457,'','".AddSlashes(pg_result($resaco,$iresaco,'telef'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,363,1126,'','".AddSlashes(pg_result($resaco,$iresaco,'z01_cgccpf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,363,2303,'','".AddSlashes(pg_result($resaco,$iresaco,'anexo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from guiabc
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($anousu != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " anousu = $anousu ";
        }
        if($tr != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " tr = '$tr' ";
        }
        if($cgcter != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " cgcter = '$cgcter' ";
        }
        if($cgcte != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " cgcte = '$cgcte' ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Guiabc nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$anousu."-".$tr."-".$cgcter."-".$cgcte;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Guiabc nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$anousu."-".$tr."-".$cgcter."-".$cgcte;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$anousu."-".$tr."-".$cgcter."-".$cgcte;
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
        $this->erro_sql   = "Record Vazio na Tabela:guiabc";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
}
?>