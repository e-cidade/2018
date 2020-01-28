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

//MODULO: dividaativa
//CLASSE DA ENTIDADE termocontrib
class cl_termocontrib { 
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
   var $parcel = 0; 
   var $contricalc = 0; 
   var $valor = 0; 
   var $juros = 0; 
   var $multa = 0; 
   var $total = 0; 
   var $desconto = 0; 
   var $numpreant = 0; 
   var $perc = 0; 
   var $vlrcor = 0; 
   var $vlrdescmul = 0; 
   var $vlrdescjur = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 parcel = int4 = Parcelamento 
                 contricalc = int4 = Codigo do calculo da contribuicao 
                 valor = float8 = valor 
                 juros = float8 = juros 
                 multa = float8 = multa 
                 total = float8 = total 
                 desconto = float8 = desconto 
                 numpreant = int4 = numpreant 
                 perc = float8 = Percentual 
                 vlrcor = float8 = Valor corrigido 
                 vlrdescmul = float8 = Desconto Multa 
                 vlrdescjur = float8 = Desconto Juros 
                 ";
   //funcao construtor da classe 
   function cl_termocontrib() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("termocontrib"); 
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
       $this->parcel = ($this->parcel == ""?@$GLOBALS["HTTP_POST_VARS"]["parcel"]:$this->parcel);
       $this->contricalc = ($this->contricalc == ""?@$GLOBALS["HTTP_POST_VARS"]["contricalc"]:$this->contricalc);
       $this->valor = ($this->valor == ""?@$GLOBALS["HTTP_POST_VARS"]["valor"]:$this->valor);
       $this->juros = ($this->juros == ""?@$GLOBALS["HTTP_POST_VARS"]["juros"]:$this->juros);
       $this->multa = ($this->multa == ""?@$GLOBALS["HTTP_POST_VARS"]["multa"]:$this->multa);
       $this->total = ($this->total == ""?@$GLOBALS["HTTP_POST_VARS"]["total"]:$this->total);
       $this->desconto = ($this->desconto == ""?@$GLOBALS["HTTP_POST_VARS"]["desconto"]:$this->desconto);
       $this->numpreant = ($this->numpreant == ""?@$GLOBALS["HTTP_POST_VARS"]["numpreant"]:$this->numpreant);
       $this->perc = ($this->perc == ""?@$GLOBALS["HTTP_POST_VARS"]["perc"]:$this->perc);
       $this->vlrcor = ($this->vlrcor == ""?@$GLOBALS["HTTP_POST_VARS"]["vlrcor"]:$this->vlrcor);
       $this->vlrdescmul = ($this->vlrdescmul == ""?@$GLOBALS["HTTP_POST_VARS"]["vlrdescmul"]:$this->vlrdescmul);
       $this->vlrdescjur = ($this->vlrdescjur == ""?@$GLOBALS["HTTP_POST_VARS"]["vlrdescjur"]:$this->vlrdescjur);
     }else{
       $this->parcel = ($this->parcel == ""?@$GLOBALS["HTTP_POST_VARS"]["parcel"]:$this->parcel);
       $this->contricalc = ($this->contricalc == ""?@$GLOBALS["HTTP_POST_VARS"]["contricalc"]:$this->contricalc);
     }
   }
   // funcao para inclusao
   function incluir ($parcel,$contricalc){ 
      $this->atualizacampos();
     if($this->valor == null ){ 
       $this->erro_sql = " Campo valor nao Informado.";
       $this->erro_campo = "valor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->juros == null ){ 
       $this->erro_sql = " Campo juros nao Informado.";
       $this->erro_campo = "juros";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->multa == null ){ 
       $this->erro_sql = " Campo multa nao Informado.";
       $this->erro_campo = "multa";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->total == null ){ 
       $this->erro_sql = " Campo total nao Informado.";
       $this->erro_campo = "total";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->desconto == null ){ 
       $this->erro_sql = " Campo desconto nao Informado.";
       $this->erro_campo = "desconto";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->numpreant == null ){ 
       $this->erro_sql = " Campo numpreant nao Informado.";
       $this->erro_campo = "numpreant";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->perc == null ){ 
       $this->erro_sql = " Campo Percentual nao Informado.";
       $this->erro_campo = "perc";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->vlrcor == null ){ 
       $this->erro_sql = " Campo Valor corrigido nao Informado.";
       $this->erro_campo = "vlrcor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->vlrdescmul == null ){ 
       $this->erro_sql = " Campo Desconto Multa nao Informado.";
       $this->erro_campo = "vlrdescmul";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->vlrdescjur == null ){ 
       $this->erro_sql = " Campo Desconto Juros nao Informado.";
       $this->erro_campo = "vlrdescjur";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->parcel = $parcel; 
       $this->contricalc = $contricalc; 
     if(($this->parcel == null) || ($this->parcel == "") ){ 
       $this->erro_sql = " Campo parcel nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->contricalc == null) || ($this->contricalc == "") ){ 
       $this->erro_sql = " Campo contricalc nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into termocontrib(
                                       parcel 
                                      ,contricalc 
                                      ,valor 
                                      ,juros 
                                      ,multa 
                                      ,total 
                                      ,desconto 
                                      ,numpreant 
                                      ,perc 
                                      ,vlrcor 
                                      ,vlrdescmul 
                                      ,vlrdescjur 
                       )
                values (
                                $this->parcel 
                               ,$this->contricalc 
                               ,$this->valor 
                               ,$this->juros 
                               ,$this->multa 
                               ,$this->total 
                               ,$this->desconto 
                               ,$this->numpreant 
                               ,$this->perc 
                               ,$this->vlrcor 
                               ,$this->vlrdescmul 
                               ,$this->vlrdescjur 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Parcelamentos de contribuicao de melhoria ($this->parcel."-".$this->contricalc) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Parcelamentos de contribuicao de melhoria já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Parcelamentos de contribuicao de melhoria ($this->parcel."-".$this->contricalc) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->parcel."-".$this->contricalc;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->parcel,$this->contricalc));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,554,'$this->parcel','I')");
       $resac = db_query("insert into db_acountkey values($acount,10185,'$this->contricalc','I')");
       $resac = db_query("insert into db_acount values($acount,1751,554,'','".AddSlashes(pg_result($resaco,0,'parcel'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1751,10185,'','".AddSlashes(pg_result($resaco,0,'contricalc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1751,556,'','".AddSlashes(pg_result($resaco,0,'valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1751,557,'','".AddSlashes(pg_result($resaco,0,'juros'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1751,558,'','".AddSlashes(pg_result($resaco,0,'multa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1751,561,'','".AddSlashes(pg_result($resaco,0,'total'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1751,559,'','".AddSlashes(pg_result($resaco,0,'desconto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1751,562,'','".AddSlashes(pg_result($resaco,0,'numpreant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1751,10184,'','".AddSlashes(pg_result($resaco,0,'perc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1751,5754,'','".AddSlashes(pg_result($resaco,0,'vlrcor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1751,9144,'','".AddSlashes(pg_result($resaco,0,'vlrdescmul'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1751,9143,'','".AddSlashes(pg_result($resaco,0,'vlrdescjur'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($parcel=null,$contricalc=null) { 
      $this->atualizacampos();
     $sql = " update termocontrib set ";
     $virgula = "";
     if(trim($this->parcel)!="" || isset($GLOBALS["HTTP_POST_VARS"]["parcel"])){ 
       $sql  .= $virgula." parcel = $this->parcel ";
       $virgula = ",";
       if(trim($this->parcel) == null ){ 
         $this->erro_sql = " Campo Parcelamento nao Informado.";
         $this->erro_campo = "parcel";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->contricalc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["contricalc"])){ 
       $sql  .= $virgula." contricalc = $this->contricalc ";
       $virgula = ",";
       if(trim($this->contricalc) == null ){ 
         $this->erro_sql = " Campo Codigo do calculo da contribuicao nao Informado.";
         $this->erro_campo = "contricalc";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["valor"])){ 
       $sql  .= $virgula." valor = $this->valor ";
       $virgula = ",";
       if(trim($this->valor) == null ){ 
         $this->erro_sql = " Campo valor nao Informado.";
         $this->erro_campo = "valor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->juros)!="" || isset($GLOBALS["HTTP_POST_VARS"]["juros"])){ 
       $sql  .= $virgula." juros = $this->juros ";
       $virgula = ",";
       if(trim($this->juros) == null ){ 
         $this->erro_sql = " Campo juros nao Informado.";
         $this->erro_campo = "juros";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->multa)!="" || isset($GLOBALS["HTTP_POST_VARS"]["multa"])){ 
       $sql  .= $virgula." multa = $this->multa ";
       $virgula = ",";
       if(trim($this->multa) == null ){ 
         $this->erro_sql = " Campo multa nao Informado.";
         $this->erro_campo = "multa";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->total)!="" || isset($GLOBALS["HTTP_POST_VARS"]["total"])){ 
       $sql  .= $virgula." total = $this->total ";
       $virgula = ",";
       if(trim($this->total) == null ){ 
         $this->erro_sql = " Campo total nao Informado.";
         $this->erro_campo = "total";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->desconto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["desconto"])){ 
       $sql  .= $virgula." desconto = $this->desconto ";
       $virgula = ",";
       if(trim($this->desconto) == null ){ 
         $this->erro_sql = " Campo desconto nao Informado.";
         $this->erro_campo = "desconto";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->numpreant)!="" || isset($GLOBALS["HTTP_POST_VARS"]["numpreant"])){ 
       $sql  .= $virgula." numpreant = $this->numpreant ";
       $virgula = ",";
       if(trim($this->numpreant) == null ){ 
         $this->erro_sql = " Campo numpreant nao Informado.";
         $this->erro_campo = "numpreant";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->perc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["perc"])){ 
       $sql  .= $virgula." perc = $this->perc ";
       $virgula = ",";
       if(trim($this->perc) == null ){ 
         $this->erro_sql = " Campo Percentual nao Informado.";
         $this->erro_campo = "perc";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->vlrcor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["vlrcor"])){ 
       $sql  .= $virgula." vlrcor = $this->vlrcor ";
       $virgula = ",";
       if(trim($this->vlrcor) == null ){ 
         $this->erro_sql = " Campo Valor corrigido nao Informado.";
         $this->erro_campo = "vlrcor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->vlrdescmul)!="" || isset($GLOBALS["HTTP_POST_VARS"]["vlrdescmul"])){ 
       $sql  .= $virgula." vlrdescmul = $this->vlrdescmul ";
       $virgula = ",";
       if(trim($this->vlrdescmul) == null ){ 
         $this->erro_sql = " Campo Desconto Multa nao Informado.";
         $this->erro_campo = "vlrdescmul";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->vlrdescjur)!="" || isset($GLOBALS["HTTP_POST_VARS"]["vlrdescjur"])){ 
       $sql  .= $virgula." vlrdescjur = $this->vlrdescjur ";
       $virgula = ",";
       if(trim($this->vlrdescjur) == null ){ 
         $this->erro_sql = " Campo Desconto Juros nao Informado.";
         $this->erro_campo = "vlrdescjur";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($parcel!=null){
       $sql .= " parcel = $this->parcel";
     }
     if($contricalc!=null){
       $sql .= " and  contricalc = $this->contricalc";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->parcel,$this->contricalc));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,554,'$this->parcel','A')");
         $resac = db_query("insert into db_acountkey values($acount,10185,'$this->contricalc','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["parcel"]))
           $resac = db_query("insert into db_acount values($acount,1751,554,'".AddSlashes(pg_result($resaco,$conresaco,'parcel'))."','$this->parcel',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["contricalc"]))
           $resac = db_query("insert into db_acount values($acount,1751,10185,'".AddSlashes(pg_result($resaco,$conresaco,'contricalc'))."','$this->contricalc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["valor"]))
           $resac = db_query("insert into db_acount values($acount,1751,556,'".AddSlashes(pg_result($resaco,$conresaco,'valor'))."','$this->valor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["juros"]))
           $resac = db_query("insert into db_acount values($acount,1751,557,'".AddSlashes(pg_result($resaco,$conresaco,'juros'))."','$this->juros',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["multa"]))
           $resac = db_query("insert into db_acount values($acount,1751,558,'".AddSlashes(pg_result($resaco,$conresaco,'multa'))."','$this->multa',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["total"]))
           $resac = db_query("insert into db_acount values($acount,1751,561,'".AddSlashes(pg_result($resaco,$conresaco,'total'))."','$this->total',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["desconto"]))
           $resac = db_query("insert into db_acount values($acount,1751,559,'".AddSlashes(pg_result($resaco,$conresaco,'desconto'))."','$this->desconto',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["numpreant"]))
           $resac = db_query("insert into db_acount values($acount,1751,562,'".AddSlashes(pg_result($resaco,$conresaco,'numpreant'))."','$this->numpreant',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["perc"]))
           $resac = db_query("insert into db_acount values($acount,1751,10184,'".AddSlashes(pg_result($resaco,$conresaco,'perc'))."','$this->perc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["vlrcor"]))
           $resac = db_query("insert into db_acount values($acount,1751,5754,'".AddSlashes(pg_result($resaco,$conresaco,'vlrcor'))."','$this->vlrcor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["vlrdescmul"]))
           $resac = db_query("insert into db_acount values($acount,1751,9144,'".AddSlashes(pg_result($resaco,$conresaco,'vlrdescmul'))."','$this->vlrdescmul',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["vlrdescjur"]))
           $resac = db_query("insert into db_acount values($acount,1751,9143,'".AddSlashes(pg_result($resaco,$conresaco,'vlrdescjur'))."','$this->vlrdescjur',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Parcelamentos de contribuicao de melhoria nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->parcel."-".$this->contricalc;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Parcelamentos de contribuicao de melhoria nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->parcel."-".$this->contricalc;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->parcel."-".$this->contricalc;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($parcel=null,$contricalc=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($parcel,$contricalc));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,554,'$parcel','E')");
         $resac = db_query("insert into db_acountkey values($acount,10185,'$contricalc','E')");
         $resac = db_query("insert into db_acount values($acount,1751,554,'','".AddSlashes(pg_result($resaco,$iresaco,'parcel'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1751,10185,'','".AddSlashes(pg_result($resaco,$iresaco,'contricalc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1751,556,'','".AddSlashes(pg_result($resaco,$iresaco,'valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1751,557,'','".AddSlashes(pg_result($resaco,$iresaco,'juros'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1751,558,'','".AddSlashes(pg_result($resaco,$iresaco,'multa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1751,561,'','".AddSlashes(pg_result($resaco,$iresaco,'total'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1751,559,'','".AddSlashes(pg_result($resaco,$iresaco,'desconto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1751,562,'','".AddSlashes(pg_result($resaco,$iresaco,'numpreant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1751,10184,'','".AddSlashes(pg_result($resaco,$iresaco,'perc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1751,5754,'','".AddSlashes(pg_result($resaco,$iresaco,'vlrcor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1751,9144,'','".AddSlashes(pg_result($resaco,$iresaco,'vlrdescmul'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1751,9143,'','".AddSlashes(pg_result($resaco,$iresaco,'vlrdescjur'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from termocontrib
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($parcel != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " parcel = $parcel ";
        }
        if($contricalc != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " contricalc = $contricalc ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Parcelamentos de contribuicao de melhoria nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$parcel."-".$contricalc;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Parcelamentos de contribuicao de melhoria nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$parcel."-".$contricalc;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$parcel."-".$contricalc;
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
        $this->erro_sql   = "Record Vazio na Tabela:termocontrib";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
}
?>